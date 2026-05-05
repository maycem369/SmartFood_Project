/**
 * SmartFood Chatbot
 * Moteur de chatbot intégré avec système de mots-clés
 */

(function () {
    // ═══════════════════════════════════════════════
    // RÈGLES / MOTS-CLÉS (Facilement extensible)
    // ═══════════════════════════════════════════════
    // ═══════════════════════════════════════════════
    // RÈGLES / MOTS-CLÉS (Multilingue)
    // ═══════════════════════════════════════════════
    const BOT_STRINGS = {
        fr: {
            welcome: "Bonjour ! 👋 Comment puis-je vous aider avec votre alimentation ou vos repas aujourd'hui ?",
            input_placeholder: "Posez une question...",
            default: "Je ne suis pas sûr d'avoir bien compris. 🤔 Pouvez-vous reformuler ? Vous pouvez me parler de vos restes pour des idées recettes, ou de vos objectifs nutritionnels !",
            typing: "En train de répondre...",
            rules: [
                { pattern: /\b(bonjour|salut|coucou|hello|hey)\b/i, response: "Bonjour ! 👋 Je suis l'assistant SmartFood. Comment puis-je vous aider aujourd'hui ?" },
                { pattern: /\b(recette|recettes|manger|repas|idée|idees)\b/i, response: "🍽️ Je peux vous suggérer des recettes ! Dites-moi ce qu'il vous reste dans votre frigo." },
                { pattern: /\b(reste|restes|frigo|anti-gaspi|gaspillage)\b/i, response: "🌱 Super initiative ! L'anti-gaspi, c'est notre spécialité." },
                { pattern: /\b(calories|calorie|nutrition|protéines|sain|poids)\b/i, response: "🥗 La nutrition est la clé de la santé. Équilibrez bien votre assiette !" },
                { pattern: /\b(merci|top|génial|super)\b/i, response: "Avec plaisir ! N'hésitez pas si vous avez d'autres questions. 😊" }
            ],
            recipe_response: (ing) => `🍽️ Excellente idée ! Avec "**${ing}**", je vous propose un délicieux **Gratin express** ou une **Poêlée gourmande**. Bon appétit ! 😋`
        },
        en: {
            welcome: "Hello! 👋 How can I help you with your nutrition or meals today?",
            input_placeholder: "Ask a question...",
            default: "I'm not sure I understood. 🤔 Can you rephrase? You can tell me about your leftovers for recipe ideas, or your nutritional goals!",
            typing: "Replying...",
            rules: [
                { pattern: /\b(hello|hi|hey|greetings)\b/i, response: "Hello! 👋 I am the SmartFood assistant. How can I help you today?" },
                { pattern: /\b(recipe|recipes|eat|meal|idea|ideas)\b/i, response: "🍽️ I can suggest recipes! Tell me what you have left in your fridge." },
                { pattern: /\b(leftover|leftovers|fridge|waste|anti-waste)\b/i, response: "🌱 Great initiative! Anti-waste is our specialty." },
                { pattern: /\b(calories|nutrition|protein|healthy|weight)\b/i, response: "🥗 Nutrition is the key to health. Balance your plate well!" },
                { pattern: /\b(thanks|thank you|great|awesome)\b/i, response: "You're welcome! Feel free to ask more questions. 😊" }
            ],
            recipe_response: (ing) => `🍽️ Great idea! With "**${ing}**", I suggest a delicious **Express Gratin** or a **Gourmet Stir-fry**. Enjoy! 😋`
        },
        ar: {
            welcome: "مرحباً! 👋 كيف يمكنني مساعدتك في تغذيتك أو وجباتك اليوم؟",
            input_placeholder: "اسأل سؤالاً...",
            default: "لست متأكداً من أنني فهمت. 🤔 هل يمكنك إعادة الصياغة؟ يمكنك إخباري عن بقايا الطعام للحصول على أفكار وصفات، أو أهدافك الغذائية!",
            typing: "جاري الرد...",
            rules: [
                { pattern: /\b(مرحبا|سلام|أهلا)\b/i, response: "مرحباً! 👋 أنا مساعد SmartFood. كيف يمكنني مساعدتك اليوم؟" },
                { pattern: /\b(وصفة|وصفات|أكل|وجبة|فكرة|أفكار)\b/i, response: "🍽️ يمكنني اقتراح وصفات! أخبرني بما تبقى في ثلاجتك." },
                { pattern: /\b(بقايا|ثلاجة|هدر|ضد الهدر)\b/i, response: "🌱 مبادرة رائعة! مكافحة الهدر هي تخصصنا." },
                { pattern: /\b(سعرات|تغذية|بروتين|صحي|وزن)\b/i, response: "🥗 التغذية هي مفتاح الصحة. وازن طبقك جيداً!" },
                { pattern: /\b(شكرا|شكراً|ممتاز|رائع)\b/i, response: "على الرحب والسعة! لا تتردد في طرح المزيد من الأسئلة. 😊" }
            ],
            recipe_response: (ing) => `🍽️ فكرة رائعة! مع "**${ing}**"، أقترح عليك **غراتان سريع** أو **مقلاة شهية**. بالعافية! 😋`
        },
        zh: {
            welcome: "你好！ 👋 今天我能如何帮助您的饮食或用餐？",
            input_placeholder: "问一个问题...",
            default: "我不确定我是否理解。 🤔 您能换个说法吗？您可以告诉我您的剩菜以获取食谱创意，或您的营养目标！",
            typing: "正在回复...",
            rules: [
                { pattern: /\b(你好|嘿|嗨)\b/i, response: "你好！ 👋 我是 SmartFood 助手。今天我能为您提供什么帮助？" },
                { pattern: /\b(食谱|菜谱|吃|用餐|主意|想法)\b/i, response: "🍽️ 我可以建议食谱！告诉我你冰箱里还剩什么。" },
                { pattern: /\b(剩菜|冰箱|浪费|防浪费)\b/i, response: "🌱 太棒了！防浪费是我们的专长。" },
                { pattern: /\b(卡路里|热量|营养|蛋白质|健康|体重)\b/i, response: "🥗 营养是健康的关键。平衡好你的盘子！" },
                { pattern: /\b(谢谢|太棒了|好极了)\b/i, response: "不客气！随时提问。 😊" }
            ],
            recipe_response: (ing) => `🍽️ 好主意！有了“**${ing}**”，我建议您做一个**快速焗烤**或**美味小炒**。请享用！ 😋`
        }
    };

    // ═══════════════════════════════════════════════
    // LOGIQUE DU MOTEUR
    // ═══════════════════════════════════════════════
    function getBotResponse(userText) {
        const lang = localStorage.getItem('sf_lang') || 'fr';
        const strings = BOT_STRINGS[lang] || BOT_STRINGS.fr;
        const textLower = userText.toLowerCase();

        // Détection d'une demande de recette avec ingrédients
        const ingredientMatch = userText.match(/(?:avec|ingr[eé]dients?\s*:|pour|with|ingredients?\s*:|for|مع|بـ|配有|用)\s+([a-zA-ZÀ-ÿ\s,.\u0600-\u06FF\u4E00-\u9FFF]+)/i);
        
        if (textLower.includes('recette') || textLower.includes('idée') || textLower.includes('recipe') || textLower.includes('idea') || textLower.includes('وصفة') || textLower.includes('فكرة') || textLower.includes('食谱') || textLower.includes('主意')) {
            if (ingredientMatch && ingredientMatch[1]) {
                return strings.recipe_response(ingredientMatch[1].trim());
            }
        }

        for (let rule of strings.rules) {
            if (rule.pattern.test(userText)) {
                return rule.response;
            }
        }
        return strings.default;
    }

    // ═══════════════════════════════════════════════
    // GÉNÉRATION DE L'INTERFACE (UI)
    // ═══════════════════════════════════════════════
    function initChatbotUI() {
        // Ajout des styles
        const style = document.createElement('style');
        style.innerHTML = `
            #sf-chatbot-container {
                position: fixed;
                bottom: 24px;
                right: 24px;
                z-index: 9999;
                font-family: 'Inter', 'Segoe UI', sans-serif;
            }
            #sf-chatbot-toggle {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--front-orange, #f39c12), #e67e22);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 28px;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border: none;
                outline: none;
            }
            #sf-chatbot-toggle:hover {
                transform: scale(1.05);
                box-shadow: 0 6px 16px rgba(243, 156, 18, 0.6);
            }
            #sf-chatbot-window {
                position: absolute;
                bottom: 80px;
                right: 0;
                width: 350px;
                height: 450px;
                background: var(--bg-card, #ffffff);
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                display: flex;
                flex-direction: column;
                overflow: hidden;
                transform-origin: bottom right;
                transition: transform 0.3s ease, opacity 0.3s ease;
                opacity: 0;
                transform: scale(0.5);
                pointer-events: none;
                border: 1px solid rgba(0,0,0,0.05);
            }
            [data-theme="dark"] #sf-chatbot-window {
                background: var(--bg-card, #2c3e50);
                border-color: rgba(255,255,255,0.05);
            }
            #sf-chatbot-window.active {
                opacity: 1;
                transform: scale(1);
                pointer-events: auto;
            }
            .sf-cb-header {
                background: linear-gradient(135deg, var(--front-accent, #2D6A4F), #1b4332);
                color: white;
                padding: 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-weight: 600;
            }
            .sf-cb-header-title { display: flex; align-items: center; gap: 8px; }
            .sf-cb-header-title span { font-size: 20px; }
            .sf-cb-close { background: none; border: none; color: white; cursor: pointer; font-size: 20px; opacity: 0.8; transition: 0.2s;}
            .sf-cb-close:hover { opacity: 1; }
            
            .sf-cb-messages {
                flex: 1;
                padding: 16px;
                overflow-y: auto;
                display: flex;
                flex-direction: column;
                gap: 12px;
                background: var(--bg-body, #f9f9f9);
            }
            [data-theme="dark"] .sf-cb-messages { background: var(--bg-body, #1a252f); }
            
            .sf-cb-msg {
                max-width: 80%;
                padding: 10px 14px;
                border-radius: 14px;
                font-size: 0.9rem;
                line-height: 1.4;
                animation: popIn 0.3s ease forwards;
            }
            .sf-cb-msg.bot {
                background: var(--bg-card, #ffffff);
                color: var(--text-dark, #2c3e50);
                align-self: flex-start;
                border-bottom-left-radius: 4px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                border: 1px solid rgba(0,0,0,0.05);
            }
            [data-theme="dark"] .sf-cb-msg.bot {
                background: #34495e;
                color: #ecf0f1;
                border-color: rgba(255,255,255,0.05);
            }
            .sf-cb-msg.user {
                background: var(--front-accent, #2D6A4F);
                color: white;
                align-self: flex-end;
                border-bottom-right-radius: 4px;
                box-shadow: 0 2px 5px rgba(45,106,79,0.2);
            }
            
            .sf-cb-input-area {
                padding: 12px;
                background: var(--bg-card, #ffffff);
                border-top: 1px solid rgba(0,0,0,0.05);
                display: flex;
                gap: 8px;
            }
            [data-theme="dark"] .sf-cb-input-area {
                background: var(--bg-card, #2c3e50);
                border-top-color: rgba(255,255,255,0.05);
            }
            .sf-cb-input-area input {
                flex: 1;
                padding: 10px 14px;
                border: 1px solid rgba(0,0,0,0.1);
                border-radius: 20px;
                outline: none;
                background: var(--bg-body, #f9f9f9);
                color: var(--text-dark, #333);
                transition: border-color 0.2s;
            }
            [data-theme="dark"] .sf-cb-input-area input {
                background: #1a252f;
                color: #ecf0f1;
                border-color: rgba(255,255,255,0.1);
            }
            .sf-cb-input-area input:focus { border-color: var(--front-accent, #2D6A4F); }
            
            .sf-cb-send {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: var(--front-orange, #f39c12);
                color: white;
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: transform 0.2s;
            }
            .sf-cb-send:hover { transform: scale(1.1); }
            .sf-cb-send i { margin-left: -2px; } /* Optic center adjustment for paper-plane */
            
            @keyframes popIn {
                0% { opacity: 0; transform: translateY(10px); }
                100% { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);

        // Construction du DOM
        const container = document.createElement('div');
        container.id = 'sf-chatbot-container';
        
        const lang = localStorage.getItem('sf_lang') || 'fr';
        const strings = BOT_STRINGS[lang] || BOT_STRINGS.fr;

        container.innerHTML = `
            <div id="sf-chatbot-window">
                <div class="sf-cb-header">
                    <div class="sf-cb-header-title">
                        <span>🤖</span> SmartAssistant
                    </div>
                    <button class="sf-cb-close" id="sf-cb-close-btn">&times;</button>
                </div>
                <div class="sf-cb-messages" id="sf-cb-messages">
                    <div class="sf-cb-msg bot">${strings.welcome}</div>
                </div>
                <div class="sf-cb-input-area">
                    <input type="text" id="sf-cb-input" placeholder="${strings.input_placeholder}" autocomplete="off">
                    <button class="sf-cb-send" id="sf-cb-send-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
            </div>
            <button id="sf-chatbot-toggle">💬</button>
        `;
        document.body.appendChild(container);

        // Logique d'interaction UI
        const toggleBtn = document.getElementById('sf-chatbot-toggle');
        const closeBtn = document.getElementById('sf-cb-close-btn');
        const chatWindow = document.getElementById('sf-chatbot-window');
        const inputField = document.getElementById('sf-cb-input');
        const sendBtn = document.getElementById('sf-cb-send-btn');
        const messagesContainer = document.getElementById('sf-cb-messages');

        toggleBtn.addEventListener('click', () => {
            chatWindow.classList.toggle('active');
            if(chatWindow.classList.contains('active')) inputField.focus();
        });

        closeBtn.addEventListener('click', () => {
            chatWindow.classList.remove('active');
        });

        function addMessage(text, sender) {
            const msgEl = document.createElement('div');
            msgEl.className = `sf-cb-msg ${sender}`;
            
            // Basic markdown parsing for bold
            const parsedText = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            msgEl.innerHTML = parsedText;
            
            messagesContainer.appendChild(msgEl);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function handleSend() {
            const text = inputField.value.trim();
            if (!text) return;
            
            // Message Utilisateur
            addMessage(text, 'user');
            inputField.value = '';

            // Simulation d'un délai de réponse (typing)
            setTimeout(() => {
                const response = getBotResponse(text);
                addMessage(response, 'bot');
            }, 600);
        }

        sendBtn.addEventListener('click', handleSend);
        inputField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') handleSend();
        });
    }

    // Initialisation quand le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChatbotUI);
    } else {
        initChatbotUI();
    }
})();
