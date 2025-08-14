let lastTime = 0;
let pollInterval = 1000;
let pollTimeout;

async function fetchMessages() {
    const res = await fetch('/api/chatget.php?since=' + lastTime);
    const retryAfter = res.headers.get('Retry-After');
    let waitMs = 3000;
    if (retryAfter) waitMs = parseInt(retryAfter, 10) * 1000;

    const data = await res.json();
    const chat = document.getElementById('chat');

    for (const msg of data.messages) {
        const div = document.createElement('div');
        div.textContent = `[${msg.time}] ${msg.author}: ${msg.message}`;
        chat.appendChild(div);
        lastTime = Math.max(lastTime, msg.timestamp);
    }

    chat.scrollTop = chat.scrollHeight;

    clearTimeout(pollTimeout);
    pollTimeout = setTimeout(fetchMessages, waitMs);
}

async function sendMessage(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    await fetch('/api/chatpost.php', {
        method: 'POST',
        body: formData
    });
    form.message.value = '';
}

document.getElementById('form').addEventListener('submit', sendMessage);

fetchMessages(); // initial poll

document.addEventListener("visibilitychange", () => {
    if (!document.hidden) fetchMessages();
});
