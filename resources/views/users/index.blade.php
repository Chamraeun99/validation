<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
    <h1>Users List</h1>
    <a href="{{ route('users.page.create') }}">Create New User</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th colspan="3">Actions</th>
            </tr>
        </thead>
        <tbody id="users-body">
        </tbody>
    </table>

    <script>
        async function loadUsers() {
            const response = await fetch('/api/users', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load users');
            }

            const payload = await response.json();
            const users = payload.data ?? payload;
            const tbody = document.getElementById('users-body');

            tbody.innerHTML = '';
            for (const user of users) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>
                        <a href="/users-page/${user.id}">View</a>
                        <a href="/users-page/${user.id}/edit">Edit</a>
                        <button type="button" data-id="${user.id}">Delete</button>
                    </td>
                `;
                tbody.appendChild(row);
            }

            for (const button of document.querySelectorAll('button[data-id]')) {
                button.addEventListener('click', async (event) => {
                    const userId = event.target.getAttribute('data-id');
                    await fetch(`/api/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    await loadUsers();
                });
            }
        }

        loadUsers().catch((error) => {
            alert(error.message);
        });
    </script>
</body>
</html>