<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg z-30">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center h-16 px-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Admin Panel</h2>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="index.php" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors <?php echo $currentPage === 'index.php' ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700 hover:bg-gray-50'; ?>">
                <i data-lucide="home" class="h-5 w-5 mr-3"></i>
                Dashboard
            </a>

            <a href="properties.php" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors <?php echo $currentPage === 'properties.php' ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700 hover:bg-gray-50'; ?>">
                <i data-lucide="building" class="h-5 w-5 mr-3"></i>
                Propiedades
            </a>

            <a href="inquiries.php" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors <?php echo $currentPage === 'inquiries.php' ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700 hover:bg-gray-50'; ?>">
                <i data-lucide="message-square" class="h-5 w-5 mr-3"></i>
                Consultas
            </a>

            <?php if ($auth->isSuperAdmin()): ?>
            <a href="users.php" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors <?php echo $currentPage === 'users.php' ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700 hover:bg-gray-50'; ?>">
                <i data-lucide="users" class="h-5 w-5 mr-3"></i>
                Usuarios
            </a>
            <?php endif; ?>

            <a href="settings.php" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors <?php echo $currentPage === 'settings.php' ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700 hover:bg-gray-50'; ?>">
                <i data-lucide="settings" class="h-5 w-5 mr-3"></i>
                Configuración
            </a>

            <a href="themes.php" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors <?php echo $currentPage === 'themes.php' ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700 hover:bg-gray-50'; ?>">
                <i data-lucide="palette" class="h-5 w-5 mr-3"></i>
                Temas
            </a>
        </nav>

        <!-- User Info -->
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <i data-lucide="user" class="h-4 w-4 text-white"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($currentUser['name']); ?></p>
                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                </div>
            </div>
            <a href="logout.php" class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                <i data-lucide="log-out" class="h-4 w-4 mr-3"></i>
                Cerrar Sesión
            </a>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
