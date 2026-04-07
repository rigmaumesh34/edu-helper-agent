<!DOCTYPE html>
<html>
<head>
    <title>EduHelper Chat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: sans-serif; max-width: 650px; margin: 40px auto; padding: 0 20px; }
        h2 { color: #2d6a4f; }
        #chatbox {
            border: 1px solid #ccc;
            border-radius: 8px;
            height: 350px;
            overflow-y: auto;
            padding: 16px;
            margin-bottom: 12px;
            background: #f9f9f9;
        }
        .user { color: #333; margin: 8px 0; }
        .bot  { color: #1a6b3c; margin: 8px 0; }
        input  { width: 75%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
        button { padding: 10px 18px; background: #2d6a4f; color: white; border: none; border-radius: 6px; cursor: pointer; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h2>EduHelper — Your Learning Assistant</h2>
    <p style="color:#666">Ask me about: <b>Solar System</b>, <b>Fractions</b>, or <b>Water Cycle</b></p>

    <div id="chatbox"></div>

    <div style="display: flex; gap: 10px; margin-top: 10px; align-items: center;">

        <input id="msg" type="text" placeholder="Type your question..."
            style="flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 6px;" />

        <button onclick="sendMsg()">Send</button>

        <button onclick="clearChat()" style="background:#dc3545;">
            Clear
        </button>

    </div>
    <script>

        async function sendMsg(autoMsg = null) {
            const input = document.getElementById('msg');
            const msg = input.value.trim();
            if (!msg) return;

            const box = document.getElementById('chatbox');

            if (!autoMsg) {
                box.innerHTML += `<p class="user"><b>You:</b> ${msg}</p>`;
                input.value = '';
            }

            try {
                const res = await fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message: msg })
                });

                const data = await res.json().catch(() => null);

                if (!res.ok) {
                    const err = (data && data.errors && data.errors.message) ? data.errors.message[0] : (data && data.error) || 'An error occurred';

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err,
                    });
                    return;
                }

                box.innerHTML += `<p class="bot"><b>EduHelper :</b> ${data.reply}</p>`;
                box.scrollTop = box.scrollHeight;
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Network error',
                    text: 'Please try again.',
                });
            }
        }
        function clearChat() {
            fetch('/clear-chat', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                const box = document.getElementById('chatbox');
                box.innerHTML = ''; // clear UI

                Swal.fire({
                    icon: 'success',
                    title: 'Chat Cleared',
                    text: 'New conversation started!'
                });
            });
        }
        function displayMessage(role, content) {
            const box = document.getElementById('chatbox');

            if (role === 'user') {
                box.innerHTML += `<p class="user"><b>You:</b> ${content}</p>`;
            } else {
                box.innerHTML += `<p class="bot"><b>EduHelper :</b> ${content}</p>`;
            }

            box.scrollTop = box.scrollHeight;
        }

        function loadHistory() {
            fetch('/chat-history')
                .then(res => res.json())
                .then(data => {
                    const box = document.getElementById('chatbox');
                    box.innerHTML = ''; // clear first

                    data.history.forEach(msg => {
                        displayMessage(msg.role, msg.content);
                    });
                });
        }
        window.onload = function () {
            loadHistory();
        };

        document.getElementById('msg').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMsg();
        });

    </script>
</body>
</html>
