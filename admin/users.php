<?php
require_once '../config/database.php';
require_once '../classes/Auth.php';

$database = new Database();
$db = $database->getConnection();

$auth = new Auth($db);
$auth->requireSuperAdmin(); // Solo super admins pueden gestionar usuarios

$currentUser = $auth->getCurrentUser();
$users = $auth->getAllUsers();
$userStats = $auth->getUserStats();

$success = '';
$error = '';

// Manejar acciones
if ($_POST) {
    $action = $_POST['action'] ?? '';
    $userId = intval($_POST['user_id'] ?? 0);
    
    switch ($action) {
        case 'approve':
            $result = $auth->approveUser($userId);
            if ($result['success']) {
                $success = $result['message'];
                $users = $auth->getAllUsers(); // Recargar lista
            } else {
                $error = $result['message'];
            }
            break;
            
        case 'reject':
            $result = $auth->rejectUser($userId);
            if ($result['success']) {
                $success = $result['message'];
                $users = $auth->getAllUsers(); // Recargar lista
            } else {
                $error = $result['message'];
            }
            break;
            
        case 'toggle_status':
            $user = $auth->getUserById($userId);
            $newStatus = !$user['is_active'];
            $result = $auth->updateUser($userId, ['is_active' => $newStatus]);
            if ($result['success']) {
                $success = $newStatus ? 'Usuario activado exitosamente.' : 'Usuario desactivado exitosamente.';
                $users = $auth->getAllUsers(); // Recargar lista
            } else {
                $error = $result['message'];
            }
            break;
            
        case 'delete':
            $result = $auth->deleteUser($userId);
            if ($result['success']) {
                $success = $result['message'];
                $users = $auth->getAllUsers(); // Recargar lista
            } else {
                $error = $result['message'];
            }
            break;
            
        case 'update_role':
            $newRole = $_POST['role'] ?? '';
            if (in_array($newRole, ['ADMIN', 'SUPER_ADMIN'])) {
                $result = $auth->updateUser($userId, ['role' => $newRole]);
                if ($result['success']) {
                    $success = 'Rol actualizado exitosamente.';
                    $users = $auth->getAllUsers(); // Recargar lista
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = 'Rol inválido.';
            }
            break;
    }
}

function getRoleBadge($role) {
    switch ($role) {
        case 'SUPER_ADMIN':
            return '<span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Super Admin</span>';
        case 'ADMIN':
            return '<span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Admin</span>';
        case 'PENDING':
            return '<span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Pendiente</span>';
        default:
            return '<span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Usuario</span>';
    }
}

function getStatusBadge($isActive) {
    return $isActive 
        ? '<span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Activo</span>'
        : '<span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">Inactivo</span>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Panel de Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <h1 class="text-2xl font-bold text-gray-900">Gestión de Usuarios</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Bienvenido, <?php echo htmlspecialchars($currentUser['name']); ?></span>
                        <a href="logout.php" class="text-red-600 hover:text-red-700">Cerrar Sesión</a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6">
                <!-- Mensajes -->
                <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    ✓ <?php echo htmlspecialchars($success); ?>
                </div>
                <?php endif; ?>

                <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i data-lucide="users" class="h-6 w-6 text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Usuarios</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $userStats['total']; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i data-lucide="user-check" class="h-6 w-6 text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Usuarios Activos</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $userStats['active']; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <i data-lucide="clock" class="h-6 w-6 text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pendientes</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $userStats['pending']; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i data-lucide="shield" class="h-6 w-6 text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Super Admins</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    <?php 
                                    $superAdmins = array_filter($userStats['by_role'], function($role) {
                                        return $role['role'] === 'SUPER_ADMIN';
                                    });
                                    echo !empty($superAdmins) ? $superAdmins[0]['count'] : 0;
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de Usuarios -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Lista de Usuarios</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Último Acceso</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creado Por</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <i data-lucide="user" class="h-5 w-5 text-gray-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['name']); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($user['role'] === 'PENDING'): ?>
                                            <?php echo getRoleBadge($user['role']); ?>
                                        <?php else: ?>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="action" value="update_role">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <select name="role" onchange="this.form.submit()" 
                                                        class="text-xs border border-gray-300 rounded px-2 py-1"
                                                        <?php echo $user['id'] == $currentUser['id'] ? 'disabled' : ''; ?>>
                                                    <option value="ADMIN" <?php echo $user['role'] === 'ADMIN' ? 'selected' : ''; ?>>Admin</option>
                                                    <option value="SUPER_ADMIN" <?php echo $user['role'] === 'SUPER_ADMIN' ? 'selected' : ''; ?>>Super Admin</option>
                                                </select>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo getStatusBadge($user['is_active']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Nunca'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo $user['created_by_name'] ?: 'Sistema'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <?php if ($user['role'] === 'PENDING'): ?>
                                                <!-- Aprobar usuario -->
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="action" value="approve">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Aprobar">
                                                        <i data-lucide="check" class="h-4 w-4"></i>
                                                    </button>
                                                </form>
                                                
                                                <!-- Rechazar usuario -->
                                                <form method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de rechazar este usuario?')">
                                                    <input type="hidden" name="action" value="reject">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Rechazar">
                                                        <i data-lucide="x" class="h-4 w-4"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <!-- Activar/Desactivar usuario -->
                                                <?php if ($user['id'] != $currentUser['id']): ?>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="action" value="toggle_status">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="<?php echo $user['is_active'] ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900'; ?>" 
                                                            title="<?php echo $user['is_active'] ? 'Desactivar' : 'Activar'; ?>">
                                                        <i data-lucide="<?php echo $user['is_active'] ? 'user-x' : 'user-check'; ?>" class="h-4 w-4"></i>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                                
                                                <!-- Editar usuario -->
                                                <button onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)" 
                                                        class="text-blue-600 hover:text-blue-900" title="Editar">
                                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                                </button>
                                                
                                                <!-- Eliminar usuario -->
                                                <?php if ($user['id'] != $currentUser['id']): ?>
                                                <form method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Editar Usuario</h3>
            </div>
            
            <form id="editForm" method="POST" class="p-6">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="user_id" id="editUserId">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                        <input type="text" name="name" id="editName" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="editEmail" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña (opcional)</label>
                        <input type="password" name="password" id="editPassword"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Dejar vacío para mantener la actual">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function editUser(user) {
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editName').value = user.name;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editPassword').value = '';
            
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>
</html>
