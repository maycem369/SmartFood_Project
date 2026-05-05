/**
 * SmartFood – chatbot.js
 * Chatbot nutritionnel flottant — multilingue, thème clair/sombre
 * Chargé avec defer dans le <head>.
 *
 * FONCTIONNEMENT :
 *  1. Essaie d'abord index.php?action=chatbot (votre backend PHP)
 *  2. Si le backend échoue / n'existe pas → fallback sur l'API Anthropic directement
 */

(function () {
    'use strict';

    // ── Endpoint backend PHP ───────────────────────────────────────────────
    const PHP_ENDPOINT = 'index.php?action=chatbot';

    // ── Traductions ────────────────────────────────────────────────────────
    const I18N = {
        fr: {
            title      : '🥗 Assistant SmartFood',
            subtitle   : 'Votre coach nutrition IA',
            placeholder: 'Posez une question nutritionnelle...',
            send       : 'Envoyer',
            thinking   : 'En train de réfléchir...',
            error      : '❌ Service indisponible. Réessayez dans un instant.',
            welcome    : '👋 Bonjour ! Je suis votre assistant nutritionnel SmartFood. Posez-moi vos questions sur la nutrition, les recettes ou votre alimentation !',
            clear      : 'Effacer',
        },
        en: {
            title      : '🥗 SmartFood Assistant',
            subtitle   : 'Your AI nutrition coach',
            placeholder: 'Ask a nutrition question...',
            send       : 'Send',
            thinking   : 'Thinking...',
            error      : '❌ Service unavailable. Please try again shortly.',
            welcome    : '👋 Hello! I\'m your SmartFood nutritional assistant. Ask me anything about nutrition, recipes, or your diet!',
            clear      : 'Clear',
        },
        ar: {
            title      : '🥗 مساعد SmartFood',
            subtitle   : 'مدرّبك الغذائي الذكي',
            placeholder: 'اطرح سؤالاً غذائياً...',
            send       : 'إرسال',
            thinking   : 'جارٍ التفكير...',
            error      : '❌ الخدمة غير متاحة. حاول لاحقاً.',
            welcome    : '👋 مرحبًا! أنا مساعدك الغذائي في SmartFood. اسألني عن التغذية أو الوصفات أو نظامك الغذائي!',
            clear      : 'مسح',
        },
        zh: {
            title      : '🥗 SmartFood 助手',
            subtitle   : '您的AI营养教练',
            placeholder: '提问营养相关问题...',
            send       : '发送',
            thinking   : '思考中...',
            error      : '❌ 服务不可用，请稍后再试。',
            welcome    : '👋 你好！我是您的 SmartFood 营养助手。请向我询问有关营养、食谱或饮食的任何问题！',
            clear      : '清除',
        }
    };

    function getLang() {
        try { return localStorage.getItem('sf_lang') || 'fr'; } catch (e) { return 'fr'; }
    }
    function t(key) {
        const lang = getLang();
        return (I18N[lang] && I18N[lang][key]) ? I18N[lang][key] : (I18N['fr'][key] || key);
    }

    // ── Prompt système nutrition ───────────────────────────────────────────
    function getSystemPrompt() {
        const lang = getLang();
        const langMap = { fr: 'français', en: 'English', ar: 'العربية', zh: '中文' };
        const langName = langMap[lang] || 'français';
        return `Tu es un assistant nutritionnel expert intégré à l'application SmartFood. 
Tu réponds UNIQUEMENT en ${langName}.
Tu es spécialisé en : nutrition, diététique, recettes saines, gestion du poids, IMC, 
allergies alimentaires, planification des repas, et anti-gaspillage alimentaire.
Tes réponses sont bienveillantes, précises et pratiques. Maximum 3 paragraphes par réponse.
Si la question n'est pas liée à la nutrition ou l'alimentation, redirige poliment vers ces sujets.`;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // CSS
    // ═══════════════════════════════════════════════════════════════════════
    const CSS = `
        #sf-chatbot-toggle {
            position: fixed; bottom: 28px; right: 28px;
            width: 60px; height: 60px; border-radius: 50%;
            background: linear-gradient(135deg, #2d6a4f 0%, #40916c 100%);
            color: #fff; font-size: 1.6rem; border: none; cursor: pointer;
            box-shadow: 0 6px 24px rgba(45,106,79,0.45); z-index: 9999;
            display: flex; align-items: center; justify-content: center;
            transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), box-shadow 0.2s;
        }
        #sf-chatbot-toggle:hover { transform: scale(1.12); box-shadow: 0 10px 32px rgba(45,106,79,0.55); }
        #sf-badge {
            position: absolute; top: -4px; right: -4px;
            width: 18px; height: 18px; background: #f4a261; border-radius: 50%;
            font-size: 0.65rem; font-weight: 700; border: 2px solid #fff;
            display: none; align-items: center; justify-content: center;
        }
        #sf-chatbot-window {
            position: fixed; bottom: 100px; right: 28px;
            width: 380px; max-height: 580px; border-radius: 20px;
            background: var(--white, #fff);
            box-shadow: 0 20px 60px rgba(0,0,0,0.18), 0 4px 20px rgba(45,106,79,0.12);
            z-index: 9998; display: none; flex-direction: column; overflow: hidden;
            border: 1px solid rgba(45,106,79,0.12);
        }
        #sf-chatbot-window.open { display: flex; animation: sf-slideUp 0.3s cubic-bezier(.34,1.56,.64,1); }
        @keyframes sf-slideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        #sf-chatbot-header {
            background: linear-gradient(135deg, #2d6a4f 0%, #40916c 100%);
            color: #fff; padding: 16px 20px;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }
        .sf-header-info { display: flex; flex-direction: column; }
        #sf-chat-title   { font-size: 1rem; font-weight: 700; line-height: 1.2; }
        #sf-chat-subtitle{ font-size: 0.75rem; opacity: 0.82; }
        .sf-header-actions { display: flex; gap: 6px; }
        .sf-header-actions button {
            background: rgba(255,255,255,0.15); border: none; color: #fff;
            width: 30px; height: 30px; border-radius: 8px; cursor: pointer;
            font-size: 0.85rem; display: flex; align-items: center; justify-content: center;
            transition: background 0.15s;
        }
        .sf-header-actions button:hover { background: rgba(255,255,255,0.28); }
        #sf-chatbot-messages {
            flex: 1; overflow-y: auto; padding: 16px;
            display: flex; flex-direction: column; gap: 12px;
            background: var(--bg-light, #f8f9fa);
            scrollbar-width: thin; scrollbar-color: #c8e6c9 transparent;
        }
        #sf-chatbot-messages::-webkit-scrollbar { width: 5px; }
        #sf-chatbot-messages::-webkit-scrollbar-thumb { background: #c8e6c9; border-radius: 4px; }
        .sf-msg {
            max-width: 82%; padding: 10px 14px; border-radius: 16px;
            font-size: 0.875rem; line-height: 1.5; word-break: break-word;
            animation: sf-fadeIn 0.2s ease;
        }
        @keyframes sf-fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .sf-msg.bot {
            align-self: flex-start; background: var(--white, #fff);
            color: var(--text-dark, #1a1a2e);
            border: 1px solid rgba(45,106,79,0.12);
            border-bottom-left-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .sf-msg.user {
            align-self: flex-end;
            background: linear-gradient(135deg, #2d6a4f 0%, #40916c 100%);
            color: #fff; border-bottom-right-radius: 4px;
        }
        .sf-typing {
            display: flex; gap: 5px; padding: 12px 14px;
            align-self: flex-start; background: var(--white, #fff);
            border: 1px solid rgba(45,106,79,0.12);
            border-radius: 16px; border-bottom-left-radius: 4px;
        }
        .sf-typing span {
            width: 7px; height: 7px; background: #2d6a4f; border-radius: 50%;
            animation: sf-bounce 1.2s infinite;
        }
        .sf-typing span:nth-child(2) { animation-delay: 0.2s; }
        .sf-typing span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes sf-bounce {
            0%,60%,100% { transform: translateY(0); opacity: 0.5; }
            30%          { transform: translateY(-6px); opacity: 1; }
        }
        #sf-chatbot-input-area {
            padding: 12px 16px; border-top: 1px solid rgba(45,106,79,0.1);
            background: var(--white, #fff); display: flex; gap: 10px; align-items: flex-end;
        }
        #sf-chatbot-input {
            flex: 1; resize: none;
            border: 1.5px solid rgba(45,106,79,0.22); border-radius: 12px;
            padding: 10px 14px; font-size: 0.875rem; font-family: inherit;
            outline: none; max-height: 110px; min-height: 42px; line-height: 1.4;
            background: var(--bg-light, #f8f9fa); color: var(--text-dark, #1a1a2e);
            transition: border-color 0.2s;
        }
        #sf-chatbot-input:focus { border-color: #2d6a4f; background: var(--white, #fff); }
        #sf-chatbot-send {
            width: 42px; height: 42px; border-radius: 12px; border: none;
            background: linear-gradient(135deg, #2d6a4f 0%, #40916c 100%);
            color: #fff; font-size: 1rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            transition: transform 0.15s, opacity 0.15s;
            box-shadow: 0 3px 10px rgba(45,106,79,0.35);
        }
        #sf-chatbot-send:hover  { transform: scale(1.08); }
        #sf-chatbot-send:active { transform: scale(0.96); }
        #sf-chatbot-send:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        /* Dark mode */
        [data-theme="dark"] #sf-chatbot-window  { background: #1e2a22; border-color: rgba(64,145,108,0.25); }
        [data-theme="dark"] #sf-chatbot-messages { background: #162018; }
        [data-theme="dark"] .sf-msg.bot  { background: #243028; color: #e8f5e9; border-color: rgba(64,145,108,0.2); }
        [data-theme="dark"] #sf-chatbot-input-area { background: #1e2a22; border-color: rgba(64,145,108,0.2); }
        [data-theme="dark"] #sf-chatbot-input { background: #162018; color: #e8f5e9; border-color: rgba(64,145,108,0.3); }
        [data-theme="dark"] #sf-chatbot-input:focus { background: #1e2a22; border-color: #40916c; }
        [data-theme="dark"] .sf-typing { background: #243028; border-color: rgba(64,145,108,0.2); }
        /* RTL */
        [dir="rtl"] #sf-chatbot-window  { right: auto; left: 28px; }
        [dir="rtl"] #sf-chatbot-toggle  { right: auto; left: 28px; }
        [dir="rtl"] .sf-msg.user { align-self: flex-start; border-bottom-right-radius: 16px; border-bottom-left-radius: 4px; }
        [dir="rtl"] .sf-msg.bot  { align-self: flex-end;   border-bottom-left-radius: 16px;  border-bottom-right-radius: 4px; }
        /* Mobile */
        @media (max-width: 480px) {
            #sf-chatbot-window { right:0; left:0; bottom:0; width:100%; border-radius:20px 20px 0 0; max-height:70vh; }
            #sf-chatbot-toggle { bottom:20px; right:16px; }
            [dir="rtl"] #sf-chatbot-toggle { right:auto; left:16px; }
        }
    `;

    // ═══════════════════════════════════════════════════════════════════════
    // BUILD UI
    // ═══════════════════════════════════════════════════════════════════════
    function buildUI() {
        const style = document.createElement('style');
        style.textContent = CSS;
        document.head.appendChild(style);

        const toggle = document.createElement('button');
        toggle.id = 'sf-chatbot-toggle';
        toggle.setAttribute('aria-label', 'Ouvrir le chatbot');
        toggle.innerHTML = `🥗<span id="sf-badge"></span>`;
        document.body.appendChild(toggle);

        const win = document.createElement('div');
        win.id = 'sf-chatbot-window';
        win.setAttribute('role', 'dialog');
        win.innerHTML = `
            <div id="sf-chatbot-header">
                <div class="sf-header-info">
                    <span id="sf-chat-title"></span>
                    <span id="sf-chat-subtitle"></span>
                </div>
                <div class="sf-header-actions">
                    <button id="sf-chat-clear">🗑️</button>
                    <button id="sf-chat-close">✕</button>
                </div>
            </div>
            <div id="sf-chatbot-messages" role="log" aria-live="polite"></div>
            <div id="sf-chatbot-input-area">
                <textarea id="sf-chatbot-input" rows="1" maxlength="1000"></textarea>
                <button id="sf-chatbot-send">➤</button>
            </div>
        `;
        document.body.appendChild(win);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ÉTAT
    // ═══════════════════════════════════════════════════════════════════════
    let isOpen    = false;
    let isLoading = false;
    let history   = []; // { role: 'user'|'assistant', content: string }

    function updateLabels() {
        const el = (id) => document.getElementById(id);
        if (el('sf-chat-title'))     el('sf-chat-title').textContent     = t('title');
        if (el('sf-chat-subtitle'))  el('sf-chat-subtitle').textContent  = t('subtitle');
        if (el('sf-chatbot-input'))  el('sf-chatbot-input').placeholder  = t('placeholder');
    }

    function toggleWindow() {
        const win = document.getElementById('sf-chatbot-window');
        const btn = document.getElementById('sf-chatbot-toggle');
        isOpen = !isOpen;
        if (isOpen) {
            win.classList.add('open');
            document.getElementById('sf-chatbot-input').focus();
            hideBadge();
        } else {
            win.classList.remove('open');
        }
        btn.innerHTML = isOpen ? `✕<span id="sf-badge"></span>` : `🥗<span id="sf-badge"></span>`;
    }

    function showBadge() {
        const b = document.getElementById('sf-badge');
        if (b && !isOpen) { b.style.display = 'flex'; }
    }
    function hideBadge() {
        const b = document.getElementById('sf-badge');
        if (b) b.style.display = 'none';
    }

    function scrollBottom() {
        const m = document.getElementById('sf-chatbot-messages');
        if (m) m.scrollTop = m.scrollHeight;
    }

    function appendMsg(role, html) {
        const msgs = document.getElementById('sf-chatbot-messages');
        const div  = document.createElement('div');
        div.className = 'sf-msg ' + role;
        div.innerHTML = html.replace(/\n/g, '<br>');
        msgs.appendChild(div);
        scrollBottom();
        return div;
    }

    function showTyping() {
        const msgs = document.getElementById('sf-chatbot-messages');
        const el   = document.createElement('div');
        el.className = 'sf-typing'; el.id = 'sf-typing';
        el.innerHTML = '<span></span><span></span><span></span>';
        msgs.appendChild(el);
        scrollBottom();
    }
    function hideTyping() {
        const el = document.getElementById('sf-typing');
        if (el) el.remove();
    }

    function clearChat() {
        document.getElementById('sf-chatbot-messages').innerHTML = '';
        history = [];
        appendMsg('bot', t('welcome'));
        history.push({ role: 'assistant', content: t('welcome') });
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ENVOI DU MESSAGE
    // Stratégie : essaie le backend PHP → fallback API Anthropic directe
    // ═══════════════════════════════════════════════════════════════════════
    async function sendMessage() {
        if (isLoading) return;
        const input = document.getElementById('sf-chatbot-input');
        const text  = input.value.trim();
        if (!text) return;

        input.value = '';
        input.style.height = 'auto';
        appendMsg('user', text);
        history.push({ role: 'user', content: text });

        isLoading = true;
        document.getElementById('sf-chatbot-send').disabled = true;
        showTyping();

        let reply = null;

        // ── 1. Tentative backend PHP ────────────────────────────────────
        try {
            const resp = await fetch(PHP_ENDPOINT, {
                method : 'POST',
                headers: { 'Content-Type': 'application/json' },
                body   : JSON.stringify({
                    message     : text,
                    conversation: history.slice(-10),
                    lang        : getLang()
                }),
                signal: AbortSignal.timeout(8000)   // 8s max
            });

            if (resp.ok) {
                const ct = resp.headers.get('content-type') || '';
                if (ct.includes('application/json')) {
                    const data = await resp.json();
                    if (data.success && data.message) {
                        reply = data.message;
                    }
                }
            }
        } catch (_) {
            // Backend non disponible → on passe au fallback
        }

        // ── 2. Fallback : réponse intelligente locale ───────────────────
        // (Répond avec des conseils nutritionnels sans API externe)
        if (!reply) {
            reply = getFallbackReply(text);
        }

        hideTyping();
        appendMsg('bot', reply);
        history.push({ role: 'assistant', content: reply });
        if (!isOpen) showBadge();

        isLoading = false;
        document.getElementById('sf-chatbot-send').disabled = false;
        document.getElementById('sf-chatbot-input').focus();
    }

    // ── Réponses de secours basées sur des règles nutritionnelles ─────────
    function getFallbackReply(question) {
        const lang = getLang();
        const q = question.toLowerCase();

        const responses = {
            fr: [
                { keys: ['imc','bmi','poids','taille','obésité','surpoids'],
                  reply: "L'IMC (Indice de Masse Corporelle) se calcule en divisant votre poids (kg) par le carré de votre taille (m²). Un IMC entre 18,5 et 24,9 est considéré normal. Votre profil SmartFood calcule votre IMC automatiquement si vous renseignez poids et taille dans Mon Profil." },
                { keys: ['calorie','énergie','kcal'],
                  reply: "Les besoins caloriques varient selon l'âge, le sexe et le niveau d'activité. En moyenne : 2000 kcal/jour pour une femme, 2500 kcal/jour pour un homme avec une activité modérée. SmartFood adapte ces recommandations à votre profil personnalisé." },
                { keys: ['protéine','muscle','masse musculaire'],
                  reply: "Les protéines sont essentielles à la construction musculaire. L'apport recommandé est de 0,8 g/kg/jour pour un adulte sédentaire, et jusqu'à 2 g/kg/jour pour les sportifs intensifs. Privilégiez les légumineuses, œufs, poisson et viande maigre." },
                { keys: ['allergi','intolérance','gluten','lactose','arachide'],
                  reply: "La gestion des allergies alimentaires est cruciale. Vous pouvez renseigner vos allergies dans votre profil SmartFood, et nos recettes seront automatiquement filtrées pour exclure les ingrédients problématiques." },
                { keys: ['recette','manger','repas','cuisine','plat','ingredient'],
                  reply: "SmartFood génère des recettes personnalisées en fonction de vos objectifs nutritionnels, vos allergies et vos préférences. Accédez à la section Recettes pour découvrir des idées adaptées à votre profil et réduire le gaspillage alimentaire !" },
                { keys: ['légume','fruit','vitamine','fibre'],
                  reply: "Les fruits et légumes sont la base d'une alimentation équilibrée. L'OMS recommande au moins 5 portions par jour (soit 400g minimum). Ils apportent vitamines, minéraux et fibres essentiels à votre santé." },
                { keys: ['hydrat','eau','boisson'],
                  reply: "L'hydratation est fondamentale : buvez 1,5 à 2 litres d'eau par jour. Cette quantité augmente avec la chaleur et l'activité physique. L'eau reste la meilleure boisson pour la santé, devant les tisanes et infusions sans sucre." },
                { keys: ['perte de poids','maigrir','régime','mincir'],
                  reply: "Pour perdre du poids sainement, créez un déficit calorique modéré de 300-500 kcal/jour. Évitez les régimes extrêmes. Privilégiez les protéines, les fibres et réduisez les sucres raffinés. L'activité physique régulière est indispensable." },
                { keys: ['sport','exercice','activité physique','musculation'],
                  reply: "L'alimentation sportive est clé : consommez des glucides complexes avant l'effort (riz, avoine, patate douce) et des protéines après (pour la récupération). Une bonne hydratation est aussi essentielle pendant et après l'exercice." },
            ],
            en: [
                { keys: ['bmi','weight','height','obesity','overweight'],
                  reply: "BMI (Body Mass Index) is calculated by dividing your weight (kg) by the square of your height (m²). A BMI between 18.5 and 24.9 is considered normal. SmartFood calculates your BMI automatically if you enter your weight and height in My Profile." },
                { keys: ['calorie','energy','kcal'],
                  reply: "Caloric needs vary by age, sex, and activity level. On average: 2000 kcal/day for women, 2500 kcal/day for men with moderate activity. SmartFood adapts these recommendations to your personal profile." },
                { keys: ['protein','muscle','muscle mass'],
                  reply: "Proteins are essential for muscle building. The recommended intake is 0.8 g/kg/day for sedentary adults, up to 2 g/kg/day for intense athletes. Prioritize legumes, eggs, fish, and lean meat." },
                { keys: ['allergy','intolerance','gluten','lactose'],
                  reply: "Managing food allergies is crucial. You can enter your allergies in your SmartFood profile, and our recipes will be automatically filtered to exclude problematic ingredients." },
                { keys: ['recipe','eat','meal','cook','dish','ingredient'],
                  reply: "SmartFood generates personalized recipes based on your nutritional goals, allergies, and preferences. Access the Recipes section to discover ideas adapted to your profile and reduce food waste!" },
                { keys: ['vegetable','fruit','vitamin','fiber'],
                  reply: "Fruits and vegetables are the basis of a balanced diet. WHO recommends at least 5 portions per day (minimum 400g). They provide essential vitamins, minerals, and fiber for your health." },
                { keys: ['hydration','water','drink'],
                  reply: "Hydration is fundamental: drink 1.5 to 2 liters of water per day. This amount increases with heat and physical activity. Water remains the best drink for health." },
                { keys: ['weight loss','diet','slim','lose weight'],
                  reply: "To lose weight healthily, create a moderate caloric deficit of 300-500 kcal/day. Avoid extreme diets. Prioritize proteins, fibers, and reduce refined sugars. Regular physical activity is essential." },
            ],
            ar: [
                { keys: ['وزن','طول','بدانة'],
                  reply: "مؤشر كتلة الجسم يُحسب بقسمة الوزن (كجم) على مربع الطول (م²). المعدل الطبيعي بين 18.5 و24.9. يحسب SmartFood مؤشر كتلة جسمك تلقائيًا عند إدخال بياناتك." },
                { keys: ['سعرات','طاقة','كالوري'],
                  reply: "تتراوح الاحتياجات السعرية بين 2000-2500 سعرة حرارية يومياً حسب الجنس والنشاط. يكيّف SmartFood هذه التوصيات لملفك الشخصي." },
                { keys: ['بروتين','عضلات'],
                  reply: "البروتينات ضرورية لبناء العضلات. الكمية الموصى بها 0.8-2 جم/كجم يوميًا. اختر البقوليات والبيض والسمك واللحم الخالي من الدهون." },
                { keys: ['حساسية','عدم تحمل','غلوتين','لاكتوز'],
                  reply: "يمكنك إدخال حساسياتك الغذائية في ملفك بـ SmartFood، وستُفلتر الوصفات تلقائيًا لاستبعاد المكونات المشكلة." },
                { keys: ['وصفة','أكل','وجبة','طعام','مكونات'],
                  reply: "يولّد SmartFood وصفات مخصصة بناءً على أهدافك الغذائية وحساسياتك وتفضيلاتك. استكشف قسم الوصفات!" },
            ],
            zh: [
                { keys: ['体重','身高','bmi','肥胖'],
                  reply: "BMI（体重指数）= 体重(kg) ÷ 身高(m)²。正常范围为18.5-24.9。在SmartFood个人资料中输入体重和身高后，系统会自动计算您的BMI。" },
                { keys: ['热量','卡路里','能量'],
                  reply: "每日热量需求因年龄、性别和活动水平而异。一般建议：女性约2000千卡/天，男性约2500千卡/天。SmartFood会根据您的个人资料提供个性化建议。" },
                { keys: ['蛋白质','肌肉'],
                  reply: "蛋白质对肌肉构建至关重要。建议摄入量为0.8-2克/公斤/天（视活动强度而定）。优选豆类、鸡蛋、鱼类和瘦肉。" },
                { keys: ['过敏','不耐受','麸质','乳糖'],
                  reply: "您可以在SmartFood个人资料中记录您的食物过敏情况，系统将自动过滤食谱以排除有问题的成分。" },
                { keys: ['食谱','饮食','餐','烹饪','食材'],
                  reply: "SmartFood根据您的营养目标、过敏情况和偏好生成个性化食谱。访问食谱部分，发现适合您的健康美食！" },
            ]
        };

        const langResp = responses[lang] || responses['fr'];
        for (const item of langResp) {
            if (item.keys.some(k => q.includes(k))) {
                return item.reply;
            }
        }

        // Réponse générique si aucun mot-clé ne correspond
        const generics = {
            fr: "Je suis votre assistant nutritionnel SmartFood ! Je peux vous aider sur : le calcul de l'IMC, les besoins caloriques, les protéines, les recettes personnalisées, la gestion des allergies, l'hydratation et bien plus. Posez-moi une question spécifique sur la nutrition ou votre alimentation !",
            en: "I'm your SmartFood nutritional assistant! I can help with: BMI calculation, caloric needs, proteins, personalized recipes, allergy management, hydration, and much more. Ask me a specific question about nutrition or your diet!",
            ar: "أنا مساعدك الغذائي في SmartFood! يمكنني المساعدة في: حساب مؤشر كتلة الجسم، احتياجات السعرات الحرارية، البروتينات، الوصفات المخصصة، إدارة الحساسية، والمزيد. اسألني سؤالاً محدداً!",
            zh: "我是您的SmartFood营养助手！我可以帮助您了解：BMI计算、热量需求、蛋白质、个性化食谱、过敏管理、水分补充等。请向我提问有关营养或饮食的具体问题！"
        };
        return generics[lang] || generics['fr'];
    }

    // ═══════════════════════════════════════════════════════════════════════
    // INITIALISATION
    // ═══════════════════════════════════════════════════════════════════════
    function init() {
        buildUI();
        updateLabels();
        appendMsg('bot', t('welcome'));
        history.push({ role: 'assistant', content: t('welcome') });

        document.getElementById('sf-chatbot-toggle').addEventListener('click', toggleWindow);
        document.getElementById('sf-chat-close').addEventListener('click', toggleWindow);
        document.getElementById('sf-chat-clear').addEventListener('click', clearChat);
        document.getElementById('sf-chatbot-send').addEventListener('click', sendMessage);

        const inp = document.getElementById('sf-chatbot-input');
        inp.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 110) + 'px';
        });
        inp.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
        });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && isOpen) toggleWindow(); });
        window.addEventListener('storage', (e) => { if (e.key === 'sf_lang') updateLabels(); });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();