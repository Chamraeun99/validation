<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form id="edit-user-form">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password (optional):</label>
        <input type="password" id="password" name="password"><br><br>

        <button type="submit">Update User</button>
    </form>

    <script>
        const userId = window.location.pathname.split('/')[2];

        async function loadUser() {
            const response = await fetch(`/api/users/${userId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load user');
            }

            const payload = await response.json();
            const user = payload.data ?? payload;

            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
        }

        document.getElementById('edit-user-form').addEventListener('submit', async (event) => {
            event.preventDefault();

            const payload = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value
            };

            const password = document.getElementById('password').value;
            if (password.trim() !== '') {
                payload.password = password;
            }

            const response = await fetch(`/api/users/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const errorPayload = await response.json();
                alert(JSON.stringify(errorPayload));
                return;
            }

            window.location.href = `/users-page/${userId}`;
        });

        loadUser().catch((error) => {
            alert(error.message);
        });
    </script>
</body>
</html>
