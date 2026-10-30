<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>InChat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/js/app.js'])
    <style>
        :root {
            --wa-green:       #00a884;
            --wa-green-dark:  #008069;
            --wa-panel-bg:    #111b21;
            --wa-panel-hdr:   #202c33;
            --wa-panel-hover: #2a3942;
            --wa-panel-active:#2a3942;
            --wa-search-inp:  #3a454c;
            --wa-chat-bg:     #0b141a;
            --wa-bubble-out:  #005c4b;
            --wa-bubble-in:   #202c33;
            --wa-text:        #e9edef;
            --wa-sub-text:    #8696a0;
            --wa-divider:     #2a3942;
            --wa-input-bg:    #202c33;
            --wa-input-area:  #1f2c34;
            --wa-tick:        #53bdeb;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; overflow: hidden; font-family: 'Segoe UI', system-ui, sans-serif; background: var(--wa-chat-bg); }

        #app { display: flex; height: 100vh; overflow: hidden; }

        /* ── SIDEBAR ── */
        #sidebar {
            width: 360px; min-width: 360px;
            display: flex; flex-direction: column;
            background: var(--wa-panel-bg);
            border-right: 1px solid var(--wa-divider);
        }

        #sb-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 16px; background: var(--wa-panel-hdr);
            height: 60px; flex-shrink: 0;
        }
        .my-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1rem; color: #fff; flex-shrink: 0; cursor: pointer;
        }
        .app-title { font-size: 1.18rem; font-weight: 600; color: var(--wa-text); margin-left: 12px; }
        .hdr-actions { display: flex; gap: 4px; margin-left: auto; }
        .icon-btn {
            width: 36px; height: 36px; border-radius: 50%; border: none; background: transparent;
            color: var(--wa-sub-text); font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: background .15s;
        }
        .icon-btn:hover { background: var(--wa-panel-hover); color: var(--wa-text); }

        #sb-search { padding: 8px 12px; background: var(--wa-panel-bg); flex-shrink: 0; }
        .search-wrap {
            background: var(--wa-search-inp); border-radius: 8px;
            display: flex; align-items: center; gap: 8px; padding: 7px 12px;
        }
        .search-wrap i { color: var(--wa-sub-text); font-size: .95rem; }
        #search-input {
            background: transparent; border: none; outline: none;
            color: var(--wa-text); font-size: .9rem; flex-grow: 1;
        }
        #search-input::placeholder { color: var(--wa-sub-text); }

        .section-label {
            padding: 6px 16px 4px; font-size: .72rem; font-weight: 600;
            letter-spacing: .6px; text-transform: uppercase; color: var(--wa-green);
        }

        #user-list { overflow-y: auto; flex-grow: 1; }
        .user-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 16px; cursor: pointer;
            border-bottom: 1px solid rgba(42,57,66,.5);
            transition: background .12s; position: relative;
        }
        .user-item:hover  { background: var(--wa-panel-hover); }
        .user-item.active { background: var(--wa-panel-active); }
        .avatar {
            width: 46px; height: 46px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1.1rem; color: #fff; flex-shrink: 0;
        }
        .uinfo { flex-grow: 1; min-width: 0; }
        .uname { font-size: .94rem; font-weight: 500; color: var(--wa-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .uemail { font-size: .78rem; color: var(--wa-sub-text); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .umeta { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }
        .online-dot {
            width: 10px; height: 10px; border-radius: 50%; background: var(--wa-green);
            border: 2px solid var(--wa-panel-bg);
            position: absolute; bottom: 12px; left: 48px;
        }
        .unread-badge {
            background: var(--wa-green); color: #fff; font-size: .72rem; font-weight: 700;
            min-width: 20px; height: 20px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; padding: 0 6px;
        }

        #sb-footer {
            padding: 10px 16px; background: var(--wa-panel-hdr);
            border-top: 1px solid var(--wa-divider); flex-shrink: 0;
        }
        #sb-footer button {
            width: 100%; background: transparent; border: 1px solid var(--wa-divider);
            color: var(--wa-sub-text); border-radius: 6px; padding: 7px; font-size: .84rem;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 7px;
            transition: background .15s, color .15s;
        }
        #sb-footer button:hover { background: var(--wa-panel-hover); color: var(--wa-text); }

        /* ── MAIN ── */
        #main { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }

        #no-chat {
            flex-grow: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            background: var(--wa-chat-bg); gap: 12px;
        }
        .nc-icon {
            width: 200px; height: 200px; border-radius: 50%;
            background: rgba(0,168,132,.07);
            display: flex; align-items: center; justify-content: center;
            font-size: 5rem; color: var(--wa-green); opacity: .7;
        }
        #no-chat h4 { color: var(--wa-text); font-weight: 300; font-size: 1.6rem; }
        #no-chat p  { color: var(--wa-sub-text); font-size: .88rem; }

        #chat-area { display: none; flex-direction: column; flex-grow: 1; overflow: hidden; }
        #chat-area.active { display: flex; }

        #chat-header {
            display: flex; align-items: center; gap: 12px; padding: 10px 16px;
            background: var(--wa-panel-hdr); height: 60px; flex-shrink: 0;
            border-bottom: 1px solid var(--wa-divider);
        }
        .ch-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1rem; color: #fff; flex-shrink: 0; cursor: pointer;
        }
        .ch-info { flex-grow: 1; min-width: 0; }
        .ch-name { font-size: .97rem; font-weight: 600; color: var(--wa-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ch-status { font-size: .76rem; color: var(--wa-green); margin-top: 1px; }
        #back-btn {
            background: transparent; border: none; color: var(--wa-sub-text);
            font-size: 1.2rem; display: none; cursor: pointer; padding: 4px 8px;
        }
        #back-btn:hover { color: var(--wa-text); }
        .ch-actions { display: flex; gap: 4px; margin-left: auto; }

        #messages {
            flex-grow: 1; overflow-y: auto; padding: 12px 8%;
            display: flex; flex-direction: column; gap: 2px;
            background-color: var(--wa-chat-bg);
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .date-sep { display: flex; align-items: center; justify-content: center; margin: 12px 0 8px; }
        .date-sep span { background: #182229; color: var(--wa-sub-text); font-size: .72rem; padding: 4px 12px; border-radius: 8px; }

        .msg-row { display: flex; align-items: flex-end; gap: 6px; margin-bottom: 2px; }
        .msg-row.mine   { flex-direction: row-reverse; }
        .msg-row.theirs { flex-direction: row; }

        .bubble {
            max-width: 62%; padding: 7px 12px 6px; border-radius: 7.5px;
            font-size: .9rem; line-height: 1.45; word-break: break-word; position: relative;
        }
        .bubble.mine   { background: var(--wa-bubble-out); color: var(--wa-text); border-top-right-radius: 0; }
        .bubble.theirs { background: var(--wa-bubble-in);  color: var(--wa-text); border-top-left-radius: 0; }

        .bubble.mine::before {
            content: ''; position: absolute; top: 0; right: -8px;
            border: 8px solid transparent; border-top-color: var(--wa-bubble-out); border-right: 0;
        }
        .bubble.theirs::before {
            content: ''; position: absolute; top: 0; left: -8px;
            border: 8px solid transparent; border-top-color: var(--wa-bubble-in); border-left: 0;
        }

        .bubble-foot { display: flex; align-items: center; justify-content: flex-end; gap: 4px; margin-top: 3px; }
        .bubble-time { font-size: .68rem; color: rgba(233,237,239,.55); }
        .bubble-tick { font-size: .78rem; color: var(--wa-tick); }

        .mini-av {
            width: 28px; height: 28px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .7rem; color: #fff;
            flex-shrink: 0; margin-bottom: 2px;
        }
        .msg-row.mine .mini-av { display: none; }

        #input-area {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 16px; background: var(--wa-input-area); flex-shrink: 0;
        }
        .input-wrap {
            flex-grow: 1; background: var(--wa-input-bg); border-radius: 24px;
            display: flex; align-items: center; padding: 9px 16px; gap: 10px;
        }
        #message-input {
            flex-grow: 1; background: transparent; border: none; outline: none;
            color: var(--wa-text); font-size: .93rem;
        }
        #message-input::placeholder { color: var(--wa-sub-text); }
        .ia-icon { color: var(--wa-sub-text); font-size: 1.35rem; cursor: pointer; flex-shrink: 0; transition: color .15s; }
        .ia-icon:hover { color: var(--wa-text); }
        #send-btn {
            width: 48px; height: 48px; border-radius: 50%; background: var(--wa-green); border: none;
            color: #fff; font-size: 1.15rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; flex-shrink: 0; transition: background .15s, transform .1s;
        }
        #send-btn:hover  { background: var(--wa-green-dark); }
        #send-btn:active { transform: scale(.93); }

        .state-msg {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            flex-grow: 1; color: var(--wa-sub-text); font-size: .87rem; gap: 8px; padding: 40px 0;
        }

        #messages::-webkit-scrollbar, #user-list::-webkit-scrollbar { width: 5px; }
        #messages::-webkit-scrollbar-thumb, #user-list::-webkit-scrollbar-thumb { background: var(--wa-divider); border-radius: 4px; }

        @media (max-width: 768px) {
            #sidebar { width: 100%; min-width: 100%; position: absolute; height: 100%; z-index: 10; }
            #sidebar.hide { display: none; }
            #back-btn { display: flex !important; }
            .bubble { max-width: 80%; }
            #messages { padding: 12px 3%; }
        }
    </style>
</head>
<body>
<div id="app">

    {{-- SIDEBAR --}}
    <div id="sidebar">
        <div id="sb-header">
            @php $myColor = '#'.substr(md5(auth()->user()->name),0,6); @endphp
            <div class="my-avatar" style="background:{{ $myColor }}">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>
            <span class="app-title">InChat</span>
            <div class="hdr-actions">
                <button class="icon-btn" title="New chat"><i class="bi bi-pencil-square"></i></button>
                <button class="icon-btn" title="Menu"><i class="bi bi-three-dots-vertical"></i></button>
            </div>
        </div>

        <div id="sb-search">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="search-input" placeholder="Search or start new chat">
            </div>
        </div>

        <div class="section-label">Colleagues</div>

        <div id="user-list">
            @foreach($users as $user)
            @php $col = '#'.substr(md5($user->name),0,6); @endphp
            <div class="user-item"
                 data-user-id="{{ $user->id }}"
                 data-user-name="{{ $user->name }}"
                 data-user-email="{{ $user->email }}"
                 data-color="{{ $col }}">
                <div class="avatar" style="background:{{ $col }}">{{ strtoupper(substr($user->name,0,1)) }}</div>
                <div class="online-dot"></div>
                <div class="uinfo">
                    <div class="uname">{{ $user->name }}</div>
                    <div class="uemail">{{ $user->email }}</div>
                </div>
                <div class="umeta"></div>
            </div>
            @endforeach
        </div>

        <div id="sb-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout &mdash; <strong style="color:var(--wa-text)">{{ auth()->user()->name }}</strong>
                </button>
            </form>
        </div>
    </div>

    {{-- MAIN --}}
    <div id="main">

        <div id="no-chat">
            <div class="nc-icon"><i class="bi bi-chat-dots"></i></div>
            <h4>InChat</h4>
            <p>Click on a colleague to start chatting</p>
            <p style="color:var(--wa-sub-text);font-size:.78rem;margin-top:8px;">
                <i class="bi bi-lock-fill me-1" style="color:var(--wa-green)"></i>
                Messages are private &amp; end-to-end encrypted
            </p>
        </div>

        <div id="chat-area">
            <div id="chat-header">
                <button id="back-btn"><i class="bi bi-arrow-left"></i></button>
                <div id="ch-avatar" class="ch-avatar">?</div>
                <div class="ch-info">
                    <div id="ch-name" class="ch-name">—</div>
                    <div class="ch-status">online</div>
                </div>
                <div class="ch-actions">
                    <button class="icon-btn" title="Video call"><i class="bi bi-camera-video"></i></button>
                    <button class="icon-btn" title="Voice call"><i class="bi bi-telephone"></i></button>
                    <button class="icon-btn" title="Search"><i class="bi bi-search"></i></button>
                    <button class="icon-btn" title="More"><i class="bi bi-three-dots-vertical"></i></button>
                </div>
            </div>

            <div id="messages"></div>

            <div id="input-area">
                <div class="input-wrap">
                    <i class="bi bi-emoji-smile ia-icon"></i>
                    <input type="text" id="message-input" placeholder="Type a message" maxlength="1000" autocomplete="off">
                    <i class="bi bi-paperclip ia-icon"></i>
                </div>
                <button id="send-btn"><i class="bi bi-send-fill" id="send-icon"></i></button>
            </div>
        </div>

    </div>
</div>

<script>
const AUTH_USER_ID = {{ auth()->id() }};
const CSRF_TOKEN   = document.querySelector('meta[name="csrf-token"]').content;
let currentUserId  = null;
let currentColor   = null;

function esc(t) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(t));
    return d.innerHTML;
}

function renderMessage(msg) {
    const mine = msg.mine;
    const cls  = mine ? 'mine' : 'theirs';
    const miniAv = mine ? '' :
        `<div class="mini-av" style="background:${currentColor}">${esc((msg.sender_name||'?').charAt(0).toUpperCase())}</div>`;
    const tick = mine ? `<i class="bi bi-check2-all bubble-tick"></i>` : '';

    return `<div class="msg-row ${cls}">
        ${miniAv}
        <div class="bubble ${cls}">
            ${esc(msg.body)}
            <div class="bubble-foot">
                <span class="bubble-time">${esc(msg.created_at)}</span>
                ${tick}
            </div>
        </div>
    </div>`;
}

function scrollBottom(smooth = false) {
    const el = document.getElementById('messages');
    el.scrollTo({ top: el.scrollHeight, behavior: smooth ? 'smooth' : 'auto' });
}

function selectUser(userId, userName, color) {
    currentUserId = userId;
    currentColor  = color;

    const av = document.getElementById('ch-avatar');
    av.textContent      = userName.charAt(0).toUpperCase();
    av.style.background = color;
    document.getElementById('ch-name').textContent = userName;

    document.getElementById('no-chat').style.display = 'none';
    document.getElementById('chat-area').classList.add('active');

    if (window.innerWidth <= 768) {
        document.getElementById('sidebar').classList.add('hide');
        document.getElementById('back-btn').style.display = 'flex';
    }

    document.querySelectorAll('.user-item').forEach(el => el.classList.remove('active'));
    document.querySelector(`.user-item[data-user-id="${userId}"]`)?.classList.add('active');

    const meta = document.querySelector(`.user-item[data-user-id="${userId}"] .umeta`);
    if (meta) meta.innerHTML = '';

    document.getElementById('message-input').focus();

    const box = document.getElementById('messages');
    box.innerHTML = `<div class="state-msg"><div class="spinner-border spinner-border-sm text-secondary" role="status"></div><span>Loading…</span></div>`;

    fetch(`/chat/messages/${userId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(msgs => {
            if (!msgs.length) {
                box.innerHTML = `<div class="state-msg"><i class="bi bi-chat-dots" style="font-size:2rem;opacity:.3"></i><span>No messages yet — say hello 👋</span></div>`;
            } else {
                box.innerHTML = msgs.map(renderMessage).join('');
            }
            scrollBottom();
        });
}

function sendMessage() {
    const input = document.getElementById('message-input');
    const body  = input.value.trim();
    if (!body || !currentUserId) return;
    input.value = '';
    input.focus();

    fetch(`/chat/send/${currentUserId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ body }),
    })
    .then(r => r.json())
    .then(msg => {
        const box = document.getElementById('messages');
        const ph  = box.querySelector('.state-msg');
        if (ph) ph.remove();
        box.insertAdjacentHTML('beforeend', renderMessage(msg));
        scrollBottom(true);
    });
}

document.getElementById('send-btn').addEventListener('click', sendMessage);
document.getElementById('message-input').addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
});

function setupEcho() {
    window.Echo.private(`chat.${AUTH_USER_ID}`)
        .listen('.MessageSent', e => {
            if (currentUserId === e.sender_id) {
                const box = document.getElementById('messages');
                const ph  = box.querySelector('.state-msg');
                if (ph) ph.remove();
                box.insertAdjacentHTML('beforeend', renderMessage({ ...e, mine: false }));
                scrollBottom(true);
            } else {
                const meta = document.querySelector(`.user-item[data-user-id="${e.sender_id}"] .umeta`);
                if (meta) {
                    let badge = meta.querySelector('.unread-badge');
                    if (!badge) {
                        badge = document.createElement('div');
                        badge.className   = 'unread-badge';
                        badge.textContent = '1';
                        meta.appendChild(badge);
                    } else {
                        badge.textContent = parseInt(badge.textContent) + 1;
                    }
                }
                const item = document.querySelector(`.user-item[data-user-id="${e.sender_id}"]`);
                if (item) item.parentNode.prepend(item);
            }
        });
}

if (window.Echo) setupEcho();
else window.addEventListener('echo:ready', setupEcho, { once: true });

document.querySelectorAll('.user-item').forEach(item => {
    item.addEventListener('click', () => {
        selectUser(parseInt(item.dataset.userId), item.dataset.userName, item.dataset.color);
    });
});

document.getElementById('back-btn').addEventListener('click', () => {
    document.getElementById('sidebar').classList.remove('hide');
    document.getElementById('back-btn').style.display = 'none';
    document.getElementById('chat-area').classList.remove('active');
    document.getElementById('no-chat').style.display  = 'flex';
    document.querySelectorAll('.user-item').forEach(el => el.classList.remove('active'));
    currentUserId = null;
});

document.getElementById('search-input').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.user-item').forEach(item => {
        const match = item.dataset.userName.toLowerCase().includes(q) || item.dataset.userEmail.toLowerCase().includes(q);
        item.style.display = match ? '' : 'none';
    });
});
</script>
</body>
</html>

