window.onload = function() {
    loadUsers();
    if (initialUserId) { 
        selectUser(initialUserId);
    }
    setInterval(loadMessages, 100); 
};

function loadUsers() {
    fetch('/includes/users/get_users.php')
    .then(response => response.json())
    .then(users => {
        const userList = document.getElementById('user-list');
        userList.innerHTML = ''; 
        users.forEach(user => {
            const userElement = document.createElement('div');
            userElement.textContent = user.id; 
            userElement.className = 'user';
            userElement.onclick = function() { selectUser(user.id); }; 
            userList.appendChild(userElement);
        });
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
}

function selectUser(userId) {
    document.getElementById('receiverId').value = userId; 
    document.getElementById('chat-box').innerHTML = ''; 
    document.getElementById('chat-header').textContent = `${userId}`; 
    loadMessages(); 
}


function sendMessage() {
    const input = document.getElementById('message-input');
    const message = input.value.trim();
    const receiverId = document.getElementById('receiverId').value; 

    input.value = '';

    if (message && receiverId) {
        const chatBox = document.getElementById('chat-box');
        const msgElement = document.createElement('div');
        msgElement.className = 'message sender';
        msgElement.textContent = message;
        chatBox.appendChild(msgElement);
        chatBox.scrollTop = chatBox.scrollHeight; 

        fetch('/includes/chat/send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `sender_id=${senderId}&receiver_id=${receiverId}&message=${encodeURIComponent(message)}`
        });
    } else if (!receiverId) {
        alert('No receiver selected!');
    }
}

function loadMessages() {
    const receiverId = document.getElementById('receiverId').value; 

    fetch(`/includes/chat/fetch_messages.php?sender_id=${senderId}&receiver_id=${receiverId}`)
    .then(response => response.json())
        .then(messages => {
            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML = ''; 
            messages.forEach(msg => {
                const msgElement = document.createElement('div');
                msgElement.className = msg.sender_id === senderId ? 'message sender' : 'message receiver';
                const msgText = document.createElement('span');
                msgText.textContent = msg.content;
                const msgTimestamp = document.createElement('div');
                msgTimestamp.textContent = `Sent at ${msg.timestamp}`;
                msgTimestamp.className = 'timestamp';
                msgElement.appendChild(msgText);
                msgElement.appendChild(msgTimestamp);
                chatBox.appendChild(msgElement);
            });
        });
}


document.getElementById('message-input').addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        sendMessage(); 
    }
});