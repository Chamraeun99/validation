<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
    <h1>User Details</h1>
    <p id="user-id">ID: </p>
    <p id="user-name">Name: </p>
    <p id="user-email">Email: </p>

    <script>
        async function loadUser() {
            const segments = window.location.pathname.split('/');
            const userId = segments[segments.length - 1];

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

            document.getElementById('user-id').textContent = `ID: ${user.id}`;
            document.getElementById('user-name').textContent = `Name: ${user.name}`;
            document.getElementById('user-email').textContent = `Email: ${user.email}`;
        }

        loadUser().catch((error) => {
            alert(error.message);
        });
    </script>

</body>
</html>