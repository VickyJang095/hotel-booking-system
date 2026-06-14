{{--
    CHATBOT WIDGET - Tripto
    Include vào layouts/app.blade.php trước </body>:
    @include('components.chatbot')
--}}

<style>
    #chatbotWidget {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    #chatbotToggle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.45);
        transition: transform .2s, box-shadow .2s;
        position: relative;
    }

    #chatbotToggle:hover {
        transform: scale(1.08);
        box-shadow: 0 6px 28px rgba(37, 99, 235, 0.55);
    }

    #chatbotToggle .icon-chat,
    #chatbotToggle .icon-close {
        position: absolute;
        transition: opacity .2s, transform .2s;
    }

    #chatbotToggle .icon-close {
        opacity: 0;
        transform: rotate(-90deg);
    }

    #chatbotWidget.open #chatbotToggle .icon-chat {
        opacity: 0;
        transform: rotate(90deg);
    }

    #chatbotWidget.open #chatbotToggle .icon-close {
        opacity: 1;
        transform: rotate(0);
    }

    #chatbotBadge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
        opacity: 0;
        transform: scale(0);
        transition: opacity .2s, transform .2s;
    }

    #chatbotBadge.show {
        opacity: 1;
        transform: scale(1);
    }

    #chatbotPanel {
        position: absolute;
        bottom: 70px;
        right: 0;
        width: 370px;
        max-height: 580px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.18);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        opacity: 0;
        transform: translateY(16px) scale(.96);
        pointer-events: none;
        transition: opacity .25s, transform .25s;
    }

    #chatbotWidget.open #chatbotPanel {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: all;
    }

    #chatbotHeader {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .chatbot-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        position: relative;
    }

    .chatbot-status {
        width: 8px;
        height: 8px;
        background: #4ade80;
        border-radius: 50%;
        border: 2px solid #fff;
        position: absolute;
        bottom: 1px;
        right: 1px;
    }

    #chatbotMessages {
        flex: 1;
        overflow-y: auto;
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        scroll-behavior: smooth;
    }

    #chatbotMessages::-webkit-scrollbar {
        width: 4px;
    }

    #chatbotMessages::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 9999px;
    }

    .msg-row {
        display: flex;
        gap: 8px;
        align-items: flex-end;
    }

    .msg-row.user {
        flex-direction: row-reverse;
    }

    .msg-avatar {
        width: 27px;
        height: 27px;
        border-radius: 50%;
        background: #eff6ff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .msg-row.user .msg-avatar {
        background: #2563eb;
    }

    .msg-bubble {
        max-width: 78%;
        padding: 9px 13px;
        border-radius: 18px;
        font-size: 13.5px;
        line-height: 1.55;
        word-break: break-word;
    }

    .msg-row.bot .msg-bubble {
        background: #f3f4f6;
        color: #111827;
        border-bottom-left-radius: 4px;
    }

    .msg-row.user .msg-bubble {
        background: #2563eb;
        color: #fff;
        border-bottom-right-radius: 4px;
    }

    .msg-time {
        font-size: 10px;
        color: #9ca3af;
        margin-top: 2px;
    }

    .msg-row.bot .msg-time {
        text-align: left;
    }

    .msg-row.user .msg-time {
        text-align: right;
    }

    /* ── Hotel suggestion cards ── */
    .hotel-suggestions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 8px;
        max-width: 100%;
    }

    .hotel-card-chat {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 8px 10px;
        text-decoration: none;
        transition: border-color .15s, box-shadow .15s, transform .15s;
        cursor: pointer;
    }

    .hotel-card-chat:hover {
        border-color: #93c5fd;
        box-shadow: 0 3px 12px rgba(37, 99, 235, 0.12);
        transform: translateY(-1px);
    }

    .hotel-card-chat img {
        width: 52px;
        height: 52px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .hotel-card-chat .hc-info {
        flex: 1;
        min-width: 0;
    }

    .hotel-card-chat .hc-name {
        font-size: 12.5px;
        font-weight: 700;
        color: #111827;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 2px;
    }

    .hotel-card-chat .hc-meta {
        font-size: 11px;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
    }

    .hc-rating {
        background: #2563eb;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 5px;
    }

    .hc-price {
        font-size: 12px;
        font-weight: 700;
        color: #2563eb;
        white-space: nowrap;
    }

    .hc-city {
        font-size: 10.5px;
        color: #9ca3af;
    }

    .hc-arrow {
        color: #d1d5db;
        flex-shrink: 0;
    }

    .hotel-card-chat:hover .hc-arrow {
        color: #2563eb;
    }

    /* ── Typing ── */
    .typing-dots {
        display: flex;
        gap: 4px;
        padding: 4px 2px;
    }

    .typing-dots span {
        width: 6px;
        height: 6px;
        background: #9ca3af;
        border-radius: 50%;
        animation: typingBounce 1.2s infinite ease-in-out;
    }

    .typing-dots span:nth-child(2) {
        animation-delay: .2s;
    }

    .typing-dots span:nth-child(3) {
        animation-delay: .4s;
    }

    @keyframes typingBounce {

        0%,
        60%,
        100% {
            transform: translateY(0)
        }

        30% {
            transform: translateY(-6px)
        }
    }

    /* ── Suggestions chips ── */
    #chatbotSuggestions {
        padding: 0 12px 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        flex-shrink: 0;
    }

    .suggestion-chip {
        font-size: 11.5px;
        padding: 5px 11px;
        border: 1px solid #dbeafe;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 9999px;
        cursor: pointer;
        transition: background .15s;
        font-weight: 500;
        white-space: nowrap;
    }

    .suggestion-chip:hover {
        background: #dbeafe;
    }

    /* ── Input ── */
    #chatbotInputArea {
        padding: 10px 12px;
        border-top: 1px solid #f3f4f6;
        display: flex;
        gap: 8px;
        align-items: flex-end;
        flex-shrink: 0;
        background: #fff;
    }

    #chatbotInput {
        flex: 1;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 9px 12px;
        font-size: 13.5px;
        color: #111827;
        outline: none;
        resize: none;
        max-height: 88px;
        line-height: 1.4;
        transition: border-color .2s;
        font-family: inherit;
    }

    #chatbotInput:focus {
        border-color: #2563eb;
    }

    #chatbotInput::placeholder {
        color: #9ca3af;
    }

    #chatbotSend {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #2563eb;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background .15s, transform .1s;
        margin-bottom: 1px;
    }

    #chatbotSend:hover {
        background: #1d4ed8;
    }

    #chatbotSend:active {
        transform: scale(.93);
    }

    #chatbotSend:disabled {
        background: #d1d5db;
        cursor: not-allowed;
    }

    .chatbot-footer {
        text-align: center;
        font-size: 10px;
        color: #d1d5db;
        padding: 0 0 8px;
    }

    @media (max-width: 480px) {
        #chatbotPanel {
            width: calc(100vw - 32px);
            right: -8px;
        }

        #chatbotWidget {
            bottom: 16px;
            right: 16px;
        }
    }
</style>

<div id="chatbotWidget">

    {{-- Toggle --}}
    <button id="chatbotToggle" onclick="toggleChatbot()" aria-label="Chat với Tripto">
        <span id="chatbotBadge">1</span>
        <svg class="icon-chat w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <svg class="icon-close w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    {{-- Panel --}}
    <div id="chatbotPanel">

        {{-- Header --}}
        <div id="chatbotHeader">
            <div class="chatbot-avatar">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2" />
                </svg>
                <div class="chatbot-status"></div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white font-semibold text-sm leading-tight">Tripto Assistant</p>
                <p class="text-blue-200 text-xs">Tư vấn đặt phòng • Trực tuyến</p>
            </div>
            <button onclick="clearChat()" title="Xóa lịch sử" class="text-blue-200 hover:text-white transition p-1 rounded-lg hover:bg-white/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div id="chatbotMessages"></div>

        {{-- Chips --}}
        <div id="chatbotSuggestions"></div>

        {{-- Input --}}
        <div id="chatbotInputArea">
            <textarea id="chatbotInput" rows="1" placeholder="Nhập câu hỏi của bạn..."></textarea>
            <button id="chatbotSend" onclick="sendMessage()" disabled>
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </div>

        <div class="chatbot-footer">Powered by Tripto AI</div>
    </div>
</div>

<script>
    (function() {
        const CHAT_URL = '{{ route("chatbot.chat") }}';
        const SUGGEST_URL = '{{ route("chatbot.suggestions") }}';
        const CSRF = '{{ csrf_token() }}';
        const HOTEL_ID = typeof window.CHATBOT_HOTEL_ID !== 'undefined' ? window.CHATBOT_HOTEL_ID : null;

        let history = [];
        let isOpen = false;
        let isTyping = false;
        let hasOpened = false;

        const widget = document.getElementById('chatbotWidget');
        const messages = document.getElementById('chatbotMessages');
        const input = document.getElementById('chatbotInput');
        const sendBtn = document.getElementById('chatbotSend');
        const badge = document.getElementById('chatbotBadge');
        const suggestionsEl = document.getElementById('chatbotSuggestions');

        // ── Toggle ──
        window.toggleChatbot = function() {
            isOpen = !isOpen;
            widget.classList.toggle('open', isOpen);
            if (isOpen && !hasOpened) {
                hasOpened = true;
                badge.classList.remove('show');
                showWelcome();
                loadSuggestions();
            }
            if (isOpen) setTimeout(() => input.focus(), 300);
        };

        // ── Welcome ──
        function showWelcome() {
            const h = new Date().getHours();
            const g = h < 12 ? 'Chào buổi sáng' : h < 18 ? 'Chào buổi chiều' : 'Chào buổi tối';
            addBotMessage(`${g}! 👋 Tôi là trợ lý AI của **Tripto**.\n\nTôi có thể giúp bạn:\n• Tư vấn & gợi ý khách sạn phù hợp\n• Giải đáp về tiện nghi, chính sách\n• So sánh các lựa chọn\n\nBạn cần hỗ trợ gì hôm nay?`, []);
        }

        // ── Load suggestion chips ──
        async function loadSuggestions() {
            try {
                const res = await fetch(SUGGEST_URL + (HOTEL_ID ? '?hotel_id=' + HOTEL_ID : ''));
                const data = await res.json();
                renderChips(data.suggestions || []);
            } catch (e) {
                renderChips(['Gợi ý khách sạn cho tôi', 'Khách sạn ở Đà Nẵng', 'Phòng dưới $80/đêm']);
            }
        }

        function renderChips(list) {
            suggestionsEl.innerHTML = '';
            list.slice(0, 4).forEach(text => {
                const chip = document.createElement('button');
                chip.className = 'suggestion-chip';
                chip.textContent = text;
                chip.onclick = () => {
                    input.value = text;
                    suggestionsEl.innerHTML = '';
                    sendMessage();
                };
                suggestionsEl.appendChild(chip);
            });
        }

        // ── Send message ──
        window.sendMessage = async function() {
            const text = input.value.trim();
            if (!text || isTyping) return;

            addUserMessage(text);
            history.push({
                role: 'user',
                content: text
            });
            input.value = '';
            autoResize();
            sendBtn.disabled = true;
            suggestionsEl.innerHTML = '';
            isTyping = true;

            const typingId = showTyping();

            try {
                const res = await fetch(CHAT_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message: text,
                        history: history.slice(-8),
                        hotel_id: HOTEL_ID,
                    }),
                });

                const data = await res.json();
                removeTyping(typingId);
                isTyping = false;

                const reply = data.reply || 'Xin lỗi, có lỗi xảy ra.';
                const suggestedHotels = data.suggested_hotels || [];

                // ✅ Hiển thị reply + hotel cards trong cùng 1 bubble
                addBotMessage(reply, suggestedHotels);
                history.push({
                    role: 'assistant',
                    content: reply
                });

                if (history.length % 6 === 0) loadSuggestions();

            } catch (e) {
                removeTyping(typingId);
                isTyping = false;
                addBotMessage('Xin lỗi, kết nối bị gián đoạn. Vui lòng thử lại! 🔄', []);
            }
        };

        // ── Add bot message với hotel cards ──
        function addBotMessage(text, hotels = []) {
            const row = document.createElement('div');
            row.className = 'msg-row bot';

            // Build hotel cards HTML
            let hotelCardsHtml = '';
            if (hotels && hotels.length > 0) {
                hotelCardsHtml = '<div class="hotel-suggestions">';
                hotels.forEach(h => {
                    const stars = '★'.repeat(h.star_rating || 0);
                    hotelCardsHtml += `
                <a href="${escAttr(h.url)}" target="_blank" class="hotel-card-chat">
                    <img src="${escAttr(h.image)}"
                         alt="${escAttr(h.name)}"
                         onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&q=80'">
                    <div class="hc-info">
                        <div class="hc-name">${escHtml(h.name)}</div>
                        <div class="hc-meta">
                            <span class="hc-rating">${h.rating}</span>
                            <span>${stars}</span>
                            <span class="hc-city">📍 ${escHtml(h.city)}</span>
                        </div>
                        <div class="hc-price">$${parseFloat(h.price).toFixed(0)}<span style="font-weight:400;color:#9ca3af;font-size:10px">/đêm</span></div>
                    </div>
                    <svg class="hc-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>`;
                });
                hotelCardsHtml += '</div>';
            }

            row.innerHTML = `
            <div class="msg-avatar">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
                </svg>
            </div>
            <div style="max-width:82%">
                <div class="msg-bubble">${formatMarkdown(text)}${hotelCardsHtml}</div>
                <div class="msg-time">${getTime()}</div>
            </div>`;

            messages.appendChild(row);
            scrollToBottom();
        }

        // ── Add user message ──
        function addUserMessage(text) {
            const row = document.createElement('div');
            row.className = 'msg-row user';
            row.innerHTML = `
            <div>
                <div class="msg-bubble">${escHtml(text)}</div>
                <div class="msg-time">${getTime()}</div>
            </div>
            <div class="msg-avatar">
                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>`;
            messages.appendChild(row);
            scrollToBottom();
        }

        // ── Typing indicator ──
        function showTyping() {
            const id = 'typing-' + Date.now();
            const row = document.createElement('div');
            row.className = 'msg-row bot';
            row.id = id;
            row.innerHTML = `
            <div class="msg-avatar">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
                </svg>
            </div>
            <div class="msg-bubble" style="padding:10px 14px">
                <div class="typing-dots"><span></span><span></span><span></span></div>
            </div>`;
            messages.appendChild(row);
            scrollToBottom();
            return id;
        }

        function removeTyping(id) {
            document.getElementById(id)?.remove();
        }

        // ── Clear ──
        window.clearChat = function() {
            messages.innerHTML = '';
            history = [];
            showWelcome();
            loadSuggestions();
        };

        // ── Helpers ──
        function scrollToBottom() {
            setTimeout(() => messages.scrollTop = messages.scrollHeight, 60);
        }

        function getTime() {
            return new Date().toLocaleTimeString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/\n/g, '<br>');
        }

        function escAttr(str) {
            return String(str).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

        function formatMarkdown(text) {
            return text
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/^•\s(.+)$/gm, '<span style="display:flex;gap:6px"><span>•</span><span>$1</span></span>')
                .replace(/\n/g, '<br>');
        }

        function autoResize() {
            input.style.height = 'auto';
            input.style.height = Math.min(input.scrollHeight, 88) + 'px';
        }

        // ── Input events ──
        input.addEventListener('input', function() {
            sendBtn.disabled = !this.value.trim();
            autoResize();
        });
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (!sendBtn.disabled) sendMessage();
            }
        });

        // ── Show badge after 3s ──
        setTimeout(() => {
            if (!hasOpened) badge.classList.add('show');
        }, 3000);
    })();
</script>