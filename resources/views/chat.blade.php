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
</head>
<body>
    <h2>EduHelper — Your Learning Assistant</h2>
    <p style="color:#666">Ask me about: <b>Solar System</b>, <b>Fractions</b>, or <b>Water Cycle</b></p>

    <div id="chatbox"></div>

    <input id="msg" type="text" placeholder="Type your question..." />
    <button onclick="sendMsg()">Send</button>

    <script>

        async function sendMsg(autoMsg = null) {
            const input = document.getElementById('msg');
            const msg = autoMsg || input.value.trim();
            if (!msg) return;

            const box = document.getElementById('chatbox');

            if (!autoMsg) {
                box.innerHTML += `<p class="user"><b>You:</b> ${msg}</p>`;
                input.value = '';
            }

            const res = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: msg })
            });

            const data = await res.json();
            box.innerHTML += `<p class="bot"><b>EduHelper :</b> ${data.reply}</p>`;
            box.scrollTop = box.scrollHeight;
        }


        document.getElementById('msg').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMsg();
        });
    </script>
</body>
</html>
