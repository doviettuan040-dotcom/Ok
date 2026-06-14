<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Handle login and logout
$loginError = handleAdminLogin();
handleAdminLogout();

// Check authentication
if (!isAdminLoggedIn()) {
    ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Lexend', sans-serif;
        }
        html, body {
            overflow: hidden;
            height: 100%;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-900 via-purple-800 to-pink-700 flex items-center justify-center p-4" style="font-family: 'Lexend', sans-serif;">

    <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 w-full max-w-md border border-white/20 shadow-2xl">
        <div class="text-center mb-6">
            <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                <i class="fa-solid fa-user-shield text-3xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Admin Panel</h2>
            <p class="text-white/60 text-sm mt-1">Đăng nhập để quản lý</p>
        </div>

        <?php if ($loginError): ?>
        <div class="bg-red-500/20 border border-red-500 text-red-200 p-3 rounded-xl mb-4 text-sm">
            <i class="fa-solid fa-circle-exclamation mr-2"></i> <?php echo htmlspecialchars($loginError); ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <input type="text" name="username" placeholder="Tên đăng nhập" required
                       class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/30">
            </div>
            <div>
                <input type="password" name="password" placeholder="Mật khẩu" required
                       class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-white/30">
            </div>
            <button type="submit" name="admin_login" 
                    class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold py-3 rounded-xl hover:shadow-lg transition duration-300">
                <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Đăng nhập
            </button>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-white/20">
            <a href="index.php" class="text-white/50 hover:text-white text-sm transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại trang chính
            </a>
        </div>
    </div>

</body>
</html>
<?php
    exit;
}

// Load data and handle submissions
$siteData = loadSiteData();
handleFormSubmissions($siteData);

// Safe HTML function
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Admin Dashboard - <?php echo safeHtml($siteData['site_info']['site_name'] ?? 'Me Profile'); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * {
      font-family: 'Lexend', sans-serif;
    }

    /* Background */
    .bg-dynamic {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -2;
      overflow: hidden;
    }

    .bg-dynamic img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .bg-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.3);
      z-index: -1;
    }

    /* Snowflakes */
    .snowflakes {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 100;
    }

    .snowflake {
      color: #fff;
      font-size: 1.2em;
      position: absolute;
      top: -10%;
      animation: fall linear infinite;
      opacity: 0.5;
    }

    @keyframes fall {
      to {
        transform: translateY(110vh) rotate(360deg);
      }
    }

    /* Particles */
    .particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
    }

    .particle {
      position: absolute;
      width: 3px;
      height: 3px;
      background: white;
      border-radius: 50%;
      opacity: 0.5;
      animation: floatParticle linear infinite;
    }

    @keyframes floatParticle {
      from {
        transform: translateY(100vh) scale(0.5);
        opacity: 0;
      }

      20% {
        opacity: 0.5;
      }

      to {
        transform: translateY(-10vh) scale(1);
        opacity: 0;
      }
    }

    /* Sidebar Desktop */
    .sidebar-desktop {
      position: fixed;
      left: 0;
      top: 0;
      width: 280px;
      height: 100%;
      background: rgba(255, 255, 255, 0.96);
      backdrop-filter: blur(20px);
      border-right: 1px solid rgba(255, 255, 255, 0.3);
      overflow-y: auto;
      z-index: 50;
    }

    /* Sidebar Mobile */
    .sidebar-mobile {
      position: fixed;
      left: 0;
      top: 0;
      width: 280px;
      height: 100%;
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(20px);
      border-right: 1px solid rgba(255, 255, 255, 0.3);
      overflow-y: auto;
      z-index: 1000;
      transform: translateX(-100%);
      transition: transform 0.3s ease;
    }

    .sidebar-mobile.open {
      transform: translateX(0);
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
      z-index: 999;
      display: none;
    }

    .overlay.show {
      display: block;
    }

    /* Main Content */
    .main-content {
      margin-left: 280px;
    }

    @media (max-width: 1024px) {
      .main-content {
        margin-left: 0;
      }

      .sidebar-desktop {
        display: none;
      }
    }

    /* Cards */
    .stat-card {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, 0.3);
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-2px);
      background: rgba(255, 255, 255, 0.95);
    }

    .section-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.4);
      overflow: hidden;
      margin-bottom: 24px;
    }

    .section-header {
      padding: 16px 20px;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(168, 85, 247, 0.05));
    }

    .section-body {
      padding: 20px;
    }

    /* Buttons */
    .btn-primary {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-success {
      background: linear-gradient(135deg, #22c55e, #16a34a);
      transition: all 0.3s ease;
    }

    .btn-success:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .btn-danger {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      transition: all 0.3s ease;
    }

    .btn-danger:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Form inputs */
    input,
    textarea,
    select {
      border: 1px solid #e2e8f0;
      transition: all 0.2s ease;
    }

    input:focus,
    textarea:focus,
    select:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      outline: none;
    }

    /* Menu items */
    .menu-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 16px;
      margin: 4px 12px;
      border-radius: 12px;
      color: #475569;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .menu-item:hover {
      background: linear-gradient(135deg, #3b82f6, #a855f7);
      color: white;
      transform: translateX(4px);
    }

    .menu-item.active {
      background: linear-gradient(135deg, #3b82f6, #a855f7);
      color: white;
    }

    /* Mobile menu button */
    .mobile-menu-btn {
      display: none;
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #3b82f6, #a855f7);
      border-radius: 25px;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      z-index: 60;
      cursor: pointer;
      border: none;
    }

    @media (max-width: 1024px) {
      .mobile-menu-btn {
        display: flex;
      }
    }

    /* Image preview */
    .image-preview {
      margin-top: 8px;
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid #e2e8f0;
      background: #f8fafc;
    }

    .image-preview img {
      width: 100%;
      max-height: 150px;
      object-fit: cover;
    }

    /* Stats grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 24px;
    }

    @media (max-width: 768px) {
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
      }
    }

    @media (max-width: 640px) {
      .section-body {
        padding: 16px;
      }
    }

    /* Animation */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .section-card {
      animation: fadeInUp 0.4s ease-out;
    }

    /* Badge */
    .badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 20px;
      font-size: 10px;
      font-weight: 600;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 6px;
    }

    ::-webkit-scrollbar-track {
      background: rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #3b82f6, #a855f7);
      border-radius: 10px;
    }

    /* ==================== MOBILE SIDEBAR STYLES ==================== */

    /* Sidebar container */
    .sidebar-mobile {
      position: fixed;
      left: 0;
      top: 0;
      width: 320px;
      height: 100%;
      background: white;
      z-index: 1000;
      transform: translateX(-100%);
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 10px 0 40px rgba(0, 0, 0, 0.1);
      overflow-y: auto;
    }

    .sidebar-mobile.open {
      transform: translateX(0);
    }

    /* Custom scrollbar */
    .sidebar-mobile::-webkit-scrollbar {
      width: 4px;
    }

    .sidebar-mobile::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    .sidebar-mobile::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #3b82f6, #a855f7);
      border-radius: 10px;
    }

    /* Menu item chính */
    .menu-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      margin: 4px 0;
      border-radius: 16px;
      cursor: pointer;
      transition: all 0.25s ease;
      background: white;
    }

    .menu-item:hover {
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
      transform: translateX(4px);
    }

    .menu-item.active {
      background: linear-gradient(135deg, #3b82f6, #a855f7);
    }

    .menu-item.active .menu-icon {
      background: rgba(255, 255, 255, 0.2);
      color: white;
    }

    .menu-item.active .flex-1 span {
      color: white;
    }

    .menu-item.active .flex-1 p {
      color: rgba(255, 255, 255, 0.7);
    }

    .menu-item.active i:last-child {
      color: white;
    }

    /* Icon trong menu */
    .menu-icon {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      transition: all 0.25s ease;
    }

    /* Footer menu item */
    .menu-item-footer {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 16px;
      margin: 4px 0;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.2s ease;
      color: #475569;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .menu-item-footer:hover {
      background: #f1f5f9;
      transform: translateX(4px);
    }

    /* Overlay */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
      z-index: 999;
      display: none;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .overlay.show {
      display: block;
      opacity: 1;
    }

    /* Animation cho menu items */
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-20px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .sidebar-mobile.open .menu-item {
      animation: slideIn 0.3s ease forwards;
    }

    .sidebar-mobile.open .menu-item:nth-child(1) {
      animation-delay: 0.05s;
    }

    .sidebar-mobile.open .menu-item:nth-child(2) {
      animation-delay: 0.1s;
    }

    .sidebar-mobile.open .menu-item:nth-child(3) {
      animation-delay: 0.15s;
    }

    .sidebar-mobile.open .menu-item:nth-child(4) {
      animation-delay: 0.2s;
    }

    .sidebar-mobile.open .menu-item:nth-child(5) {
      animation-delay: 0.25s;
    }

    .sidebar-mobile.open .menu-item:nth-child(6) {
      animation-delay: 0.3s;
    }

    .sidebar-mobile.open .menu-item:nth-child(7) {
      animation-delay: 0.35s;
    }

    .sidebar-mobile.open .menu-item:nth-child(8) {
      animation-delay: 0.4s;
    }

    /* ==================== DESKTOP SIDEBAR STYLES ==================== */

    /* CHỈ HIỂN THỊ TRÊN DESKTOP (màn hình >= 1024px) */
    @media (min-width: 1024px) {
      .sidebar-desktop {
        position: fixed;
        left: 0;
        top: 0;
        width: 280px;
        height: 100%;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-right: 1px solid rgba(0, 0, 0, 0.05);
        overflow-y: auto;
        z-index: 50;
        display: flex !important;
        flex-direction: column;
      }

      /* Main content margin khi có sidebar */
      .main-content {
        margin-left: 280px;
      }
    }

    /* ẨN TRÊN MOBILE (màn hình < 1024px) */
    @media (max-width: 1023px) {
      .sidebar-desktop {
        display: none !important;
      }

      .main-content {
        margin-left: 0 !important;
      }
    }

    /* Custom scrollbar */
    .sidebar-desktop::-webkit-scrollbar {
      width: 4px;
    }

    .sidebar-desktop::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    .sidebar-desktop::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
    }

    .sidebar-desktop::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    /* Header */
    .sidebar-header {
      padding: 24px 20px;
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
      border-bottom: 1px solid #e2e8f0;
    }

    /* Avatar admin */
    .avatar-admin {
      position: relative;
      width: 48px;
      height: 48px;
    }

    .avatar-admin img {
      width: 100%;
      height: 100%;
      border-radius: 16px;
      object-fit: cover;
      border: 2px solid white;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .avatar-status {
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 12px;
      height: 12px;
      background: #22c55e;
      border: 2px solid white;
      border-radius: 50%;
    }

    /* Stats quick */
    .stats-quick {
      display: flex;
      gap: 12px;
      margin-top: 16px;
    }

    .stat-item {
      flex: 1;
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .stat-icon {
      width: 32px;
      height: 32px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .stat-icon i {
      font-size: 14px;
    }

    .stat-info {
      flex: 1;
    }

    .stat-value {
      font-size: 16px;
      font-weight: 700;
      color: #1e293b;
      line-height: 1.2;
    }

    .stat-label {
      font-size: 10px;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    /* Navigation */
    .sidebar-nav {
      flex: 1;
      padding: 20px;
    }

    .nav-title {
      font-size: 10px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #94a3b8;
      margin-bottom: 16px;
      padding-left: 12px;
    }

    .nav-menu {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    /* Menu item */
    .menu-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 12px;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.25s ease;
      background: transparent;
      position: relative;
    }

    .menu-item:hover {
      background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
      transform: translateX(4px);
    }

    .menu-item.active {
      background: linear-gradient(135deg, #3b82f6, #a855f7);
    }

    .menu-item.active .menu-icon {
      background: rgba(255, 255, 255, 0.2);
      color: white;
    }

    .menu-item.active .menu-text span {
      color: white;
    }

    .menu-item.active .menu-text p {
      color: rgba(255, 255, 255, 0.7);
    }

    .menu-item.active .menu-arrow {
      color: white;
      opacity: 1;
    }

    /* Menu icon */
    .menu-icon {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f1f5f9;
      color: #3b82f6;
      font-size: 16px;
      transition: all 0.25s ease;
    }

    .menu-text {
      flex: 1;
    }

    .menu-text span {
      display: block;
      font-size: 13px;
      font-weight: 500;
      color: #1e293b;
      margin-bottom: 2px;
    }

    .menu-text p {
      font-size: 10px;
      color: #94a3b8;
    }

    .menu-arrow {
      font-size: 12px;
      color: #cbd5e1;
      opacity: 0;
      transition: all 0.25s ease;
    }

    .menu-item:hover .menu-arrow {
      opacity: 1;
      transform: translateX(4px);
    }

    /* Footer */
    .sidebar-footer {
      padding: 16px 20px 24px;
      border-top: 1px solid #e2e8f0;
      background: white;
    }

    .footer-action {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 12px;
      border-radius: 12px;
      color: #475569;
      font-size: 13px;
      font-weight: 500;
      transition: all 0.2s ease;
      margin-bottom: 8px;
      text-decoration: none;
    }

    .footer-action:last-child {
      margin-bottom: 0;
    }

    .footer-action:hover {
      background: #f1f5f9;
      transform: translateX(4px);
    }

    .footer-action i:first-child {
      width: 20px;
      font-size: 14px;
    }

    .footer-action.logout {
      color: #ef4444;
    }

    .footer-action.logout:hover {
      background: #fef2f2;
    }

    /* Tooltip cho menu (optional) */
    .menu-item[data-tooltip] {
      position: relative;
    }

    .menu-item[data-tooltip]:hover::after {
      content: attr(data-tooltip);
      position: absolute;
      left: 100%;
      top: 50%;
      transform: translateY(-50%);
      background: #1e293b;
      color: white;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 11px;
      white-space: nowrap;
      margin-left: 12px;
      z-index: 100;
    }
  </style>
</head>

<body>

  <!-- Dynamic Background -->
  <div class="bg-dynamic">
    <picture>
      <source media="(min-width: 769px)" srcset="<?php echo safeHtml($siteData['background']['url'] ?? ''); ?>">
      <source media="(max-width: 768px)" srcset="<?php echo safeHtml($siteData['background']['mobile_url'] ?? ''); ?>">
      <img src="<?php echo safeHtml($siteData['background']['url'] ?? ''); ?>" alt="Background">
    </picture>
  </div>
  <div class="bg-overlay"></div>

  <!-- Snowflakes -->
  <div class="snowflakes">
    <div class="snowflake" style="left:10%; animation-duration:10s;">❄</div>
    <div class="snowflake" style="left:30%; animation-duration:8s;">•</div>
    <div class="snowflake" style="left:50%; animation-duration:12s;">❄</div>
    <div class="snowflake" style="left:70%; animation-duration:9s;">•</div>
    <div class="snowflake" style="left:85%; animation-duration:15s;">❆</div>
    <div class="snowflake" style="left:20%; animation-duration:11s;">❄</div>
    <div class="snowflake" style="left:60%; animation-duration:7s;">•</div>
    <div class="snowflake" style="left:90%; animation-duration:13s;">❆</div>
  </div>

  <!-- Particles -->
  <div class="particles" id="particles"></div>

  <!-- Desktop Sidebar -->
  <div class="sidebar-desktop">
    <!-- Header với avatar và thông tin admin -->
    <div class="sidebar-header">
      <div class="flex items-center gap-3 mb-4">
        <div class="avatar-admin">
          <img src="<?php echo safeHtml($siteData['profile']['avatar'] ?? 'https://ui-avatars.com/api/?background=3b82f6&color=fff&name=Admin'); ?>" alt="Admin Avatar" onerror="this.src='https://ui-avatars.com/api/?background=3b82f6&color=fff&name=ADMIN'">
          <div class="avatar-status"></div>
        </div>
        <div class="flex-1">
          <h2 class="font-bold text-gray-800 text-sm"><?php echo safeHtml($siteData['profile']['name'] ?? 'Administrator'); ?></h2>
          <p class="text-xs text-gray-400">Super Admin</p>
        </div>
      </div>

      <!-- Thống kê nhanh -->
      <div class="stats-quick">
        <div class="stat-item">
          <div class="stat-icon bg-blue-100">
            <i class="fa-solid fa-folder-tree text-blue-500"></i>
          </div>
          <div class="stat-info">
            <p class="stat-value"><?php echo count($siteData['categories'] ?? []); ?></p>
            <p class="stat-label">Danh mục</p>
          </div>
        </div>
        <div class="stat-item">
          <div class="stat-icon bg-green-100">
            <i class="fa-solid fa-music text-green-500"></i>
          </div>
          <div class="stat-info">
            <p class="stat-value"><?php echo count($siteData['music']['song_names'] ?? []); ?></p>
            <p class="stat-label">Bài hát</p>
          </div>
        </div>
        <div class="stat-item">
          <div class="stat-icon bg-purple-100">
            <i class="fa-solid fa-link text-purple-500"></i>
          </div>
          <div class="stat-info">
            <p class="stat-value"><?php echo count($siteData['social_links'] ?? []); ?></p>
            <p class="stat-label">Liên kết</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Menu chính -->
    <div class="sidebar-nav">
      <p class="nav-title">Điều hướng chính</p>
      <nav class="nav-menu">
        <div class="menu-item" data-section="settings" onclick="scrollToSection('settings')">
          <div class="menu-icon">
            <i class="fa-solid fa-gear"></i>
          </div>
          <div class="menu-text">
            <span>Cài đặt chung</span>
            <p>Cấu hình website</p>
          </div>
          <i class="fa-solid fa-angle-right menu-arrow"></i>
        </div>

        <div class="menu-item" data-section="profile" onclick="scrollToSection('profile')">
          <div class="menu-icon">
            <i class="fa-solid fa-user"></i>
          </div>
          <div class="menu-text">
            <span>Hồ sơ</span>
            <p>Thông tin cá nhân</p>
          </div>
          <i class="fa-solid fa-angle-right menu-arrow"></i>
        </div>

        <div class="menu-item" data-section="categories" onclick="scrollToSection('categories')">
          <div class="menu-icon">
            <i class="fa-solid fa-folder-tree"></i>
          </div>
          <div class="menu-text">
            <span>Danh mục & Mục</span>
            <p>Quản lý nội dung</p>
          </div>
          <i class="fa-solid fa-angle-right menu-arrow"></i>
        </div>

        <div class="menu-item" data-section="social" onclick="scrollToSection('social')">
          <div class="menu-icon">
            <i class="fa-solid fa-share-alt"></i>
          </div>
          <div class="menu-text">
            <span>Liên kết mạng xã hội</span>
            <p>Social media</p>
          </div>
          <i class="fa-solid fa-angle-right menu-arrow"></i>
        </div>

        <div class="menu-item" data-section="music" onclick="scrollToSection('music')">
          <div class="menu-icon">
            <i class="fa-solid fa-music"></i>
          </div>
          <div class="menu-text">
            <span>Âm nhạc</span>
            <p>Playlist nền</p>
          </div>
          <i class="fa-solid fa-angle-right menu-arrow"></i>
        </div>

        <div class="menu-item" data-section="navbar" onclick="scrollToSection('navbar')">
          <div class="menu-icon">
            <i class="fa-solid fa-bars"></i>
          </div>
          <div class="menu-text">
            <span>Navbar Cards</span>
            <p>Card hiển thị</p>
          </div>
          <i class="fa-solid fa-angle-right menu-arrow"></i>
        </div>

        <div class="menu-item" data-section="sidebar_menu" onclick="scrollToSection('sidebar_menu')">
          <div class="menu-icon">
            <i class="fa-solid fa-bars-staggered"></i>
          </div>
          <div class="menu-text">
            <span>Sidebar Menu</span>
            <p>Menu bên trái</p>
          </div>
          <i class="fa-solid fa-angle-right menu-arrow"></i>
        </div>

        <div class="menu-item" data-section="background_section" onclick="scrollToSection('background_section')">
          <div class="menu-icon">
            <i class="fa-solid fa-image"></i>
          </div>
          <div class="menu-text">
            <span>Background</span>
            <p>Ảnh nền website</p>
          </div>
          <i class="fa-solid fa-angle-right menu-arrow"></i>
        </div>
      </nav>
    </div>

    <!-- Footer actions -->
    <div class="sidebar-footer">
      <a href="index.php" target="_blank" class="footer-action">
        <i class="fa-solid fa-eye"></i>
        <span>Xem website</span>
        <i class="fa-solid fa-external-link-alt text-xs opacity-50"></i>
      </a>
      <a href="?logout=1" class="footer-action logout">
        <i class="fa-solid fa-sign-out-alt"></i>
        <span>Đăng xuất</span>
      </a>
    </div>
  </div>

  <!-- Mobile Sidebar -->
  <div class="sidebar-mobile" id="mobileSidebar">
    <!-- Header với gradient và avatar -->
    <div class="relative bg-gradient-to-br from-blue-600 to-purple-700 p-5 pt-8">
      <!-- Nút đóng -->
      <button onclick="closeMobileSidebar()" class="absolute top-4 right-4 text-white/70 hover:text-white transition">
        <i class="fa-solid fa-times text-xl"></i>
      </button>

      <!-- Avatar và thông tin admin -->
      <div class="flex items-center gap-3 mb-4">
        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/30 shadow-lg">
          <i class="fa-solid fa-user-shield text-white text-2xl"></i>
        </div>
        <div>
          <h2 class="font-bold text-white text-lg">Admin Panel</h2>
          <p class="text-white/60 text-xs">Quản lý nội dung</p>
        </div>
      </div>

      <!-- Thống kê nhanh -->
      <div class="grid grid-cols-2 gap-2 mt-4">
        <div class="bg-white/10 rounded-xl p-2 text-center backdrop-blur-sm">
          <p class="text-white/70 text-[10px]">Danh mục</p>
          <p class="text-white font-bold text-sm"><?php echo count($siteData['categories'] ?? []); ?></p>
        </div>
        <div class="bg-white/10 rounded-xl p-2 text-center backdrop-blur-sm">
          <p class="text-white/70 text-[10px]">Bài hát</p>
          <p class="text-white font-bold text-sm"><?php echo count($siteData['music']['song_names'] ?? []); ?></p>
        </div>
      </div>
    </div>

    <!-- Menu chính -->
    <div class="p-4">
      <p class="text-xs text-gray-400 uppercase tracking-wider mb-3 px-3">Điều hướng chính</p>
      <nav class="space-y-1">
        <div class="menu-item" data-section="settings" onclick="scrollToSection('settings'); closeMobileSidebar();">
          <div class="menu-icon bg-indigo-100 text-indigo-600">
            <i class="fa-solid fa-gear"></i>
          </div>
          <div class="flex-1">
            <span class="font-medium">Cài đặt chung</span>
            <p class="text-xs text-gray-400">Cấu hình website</p>
          </div>
          <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
        </div>

        <div class="menu-item" data-section="profile" onclick="scrollToSection('profile'); closeMobileSidebar();">
          <div class="menu-icon bg-blue-100 text-blue-600">
            <i class="fa-solid fa-user"></i>
          </div>
          <div class="flex-1">
            <span class="font-medium">Hồ sơ</span>
            <p class="text-xs text-gray-400">Thông tin cá nhân</p>
          </div>
          <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
        </div>

        <div class="menu-item" data-section="categories" onclick="scrollToSection('categories'); closeMobileSidebar();">
          <div class="menu-icon bg-purple-100 text-purple-600">
            <i class="fa-solid fa-folder-tree"></i>
          </div>
          <div class="flex-1">
            <span class="font-medium">Danh mục & Mục</span>
            <p class="text-xs text-gray-400">Quản lý nội dung</p>
          </div>
          <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
        </div>

        <div class="menu-item" data-section="social" onclick="scrollToSection('social'); closeMobileSidebar();">
          <div class="menu-icon bg-pink-100 text-pink-600">
            <i class="fa-solid fa-share-alt"></i>
          </div>
          <div class="flex-1">
            <span class="font-medium">Liên kết MXH</span>
            <p class="text-xs text-gray-400">Mạng xã hội</p>
          </div>
          <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
        </div>

        <div class="menu-item" data-section="music" onclick="scrollToSection('music'); closeMobileSidebar();">
          <div class="menu-icon bg-green-100 text-green-600">
            <i class="fa-solid fa-music"></i>
          </div>
          <div class="flex-1">
            <span class="font-medium">Âm nhạc</span>
            <p class="text-xs text-gray-400">Playlist nền</p>
          </div>
          <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
        </div>

        <div class="menu-item" data-section="navbar" onclick="scrollToSection('navbar'); closeMobileSidebar();">
          <div class="menu-icon bg-orange-100 text-orange-600">
            <i class="fa-solid fa-bars"></i>
          </div>
          <div class="flex-1">
            <span class="font-medium">Navbar Cards</span>
            <p class="text-xs text-gray-400">Card hiển thị</p>
          </div>
          <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
        </div>

        <div class="menu-item" data-section="sidebar_menu" onclick="scrollToSection('sidebar_menu'); closeMobileSidebar();">
          <div class="menu-icon bg-cyan-100 text-cyan-600">
            <i class="fa-solid fa-bars-staggered"></i>
          </div>
          <div class="flex-1">
            <span class="font-medium">Sidebar Menu</span>
            <p class="text-xs text-gray-400">Menu bên trái</p>
          </div>
          <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
        </div>

        <div class="menu-item" data-section="background_section" onclick="scrollToSection('background_section'); closeMobileSidebar();">
          <div class="menu-icon bg-teal-100 text-teal-600">
            <i class="fa-solid fa-image"></i>
          </div>
          <div class="flex-1">
            <span class="font-medium">Background</span>
            <p class="text-xs text-gray-400">Ảnh nền website</p>
          </div>
          <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
        </div>
      </nav>
    </div>

    <!-- Footer menu -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100 bg-white/50 backdrop-blur-sm">
      <a href="index.php" target="_blank" class="menu-item-footer" onclick="closeMobileSidebar()">
        <i class="fa-solid fa-eye text-blue-500"></i>
        <span>Xem website</span>
      </a>
      <a href="?logout=1" class="menu-item-footer text-red-500" onclick="closeMobileSidebar()">
        <i class="fa-solid fa-sign-out-alt"></i>
        <span>Đăng xuất</span>
      </a>
    </div>
  </div>
  <div class="overlay" id="overlay" onclick="closeMobileSidebar()"></div>

  <!-- Mobile Menu Button -->
  <button class="mobile-menu-btn" onclick="openMobileSidebar()">
    <i class="fa-solid fa-bars-staggered"></i>
  </button>

  <!-- Main Content -->
  <div class="main-content">
    <div class="p-4 md:p-6 max-w-7xl mx-auto">

      <!-- Header -->
      <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-lg mb-6 p-4 md:p-5 border border-white/30">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
          <div>
            <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
              Admin Dashboard
            </h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý toàn bộ nội dung website</p>
          </div>
          <div class="flex gap-2">
            <a href="index.php" target="_blank" class="btn-success px-4 py-2 text-white rounded-xl text-sm flex items-center gap-2">
              <i class="fa-solid fa-eye"></i>
              <span class="hidden sm:inline">Xem website</span>
            </a>
            <a href="?logout=1" class="btn-danger px-4 py-2 text-white rounded-xl text-sm flex items-center gap-2">
              <i class="fa-solid fa-sign-out-alt"></i>
              <span class="hidden sm:inline">Đăng xuất</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Message -->
      <?php if (isset($_SESSION['admin_message'])): ?>
      <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 rounded-xl mb-6 text-sm">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-check-circle text-green-500"></i>
          <?php
                        echo safeHtml($_SESSION['admin_message']);
                        unset($_SESSION['admin_message']);
                        ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Stats Cards -->
      <div class="stats-grid">
        <div class="stat-card p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-xs uppercase tracking-wide">Danh mục</p>
              <p class="text-2xl font-bold text-gray-800"><?php echo count($siteData['categories'] ?? []); ?></p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
              <i class="fa-solid fa-folder-tree text-blue-500 text-xl"></i>
            </div>
          </div>
        </div>
        <div class="stat-card p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-xs uppercase tracking-wide">Mục nhập</p>
              <p class="text-2xl font-bold text-gray-800">
                <?php
                                $total = 0;
                                foreach ($siteData['categories'] ?? [] as $items) {
                                    $total += count($items);
                                }
                                echo $total;
                                ?>
              </p>
            </div>
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
              <i class="fa-solid fa-list-ul text-purple-500 text-xl"></i>
            </div>
          </div>
        </div>
        <div class="stat-card p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-xs uppercase tracking-wide">Liên kết</p>
              <p class="text-2xl font-bold text-gray-800"><?php echo count($siteData['social_links'] ?? []); ?></p>
            </div>
            <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center">
              <i class="fa-solid fa-share-alt text-pink-500 text-xl"></i>
            </div>
          </div>
        </div>
        <div class="stat-card p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 text-xs uppercase tracking-wide">Bài hát</p>
              <p class="text-2xl font-bold text-gray-800"><?php echo count($siteData['music']['song_names'] ?? []); ?></p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
              <i class="fa-solid fa-music text-green-500 text-xl"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- ==================== SETTINGS SECTION ==================== -->
      <div id="settings" class="section-card scroll-mt">
        <div class="section-header">
          <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-gear text-indigo-500"></i>
            Cài đặt chung
          </h2>
          <p class="text-xs text-gray-500 mt-1">Cấu hình thông tin cơ bản của website</p>
        </div>
        <div class="section-body">
          <form method="POST" action="" class="space-y-5">

            <!-- ========== Thông tin website ========== -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
              <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-globe text-blue-500"></i>
                Thông tin website
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-tag mr-1"></i> Tên website
                  </label>
                  <input type="text" name="site_name" value="<?php echo safeHtml($siteData['site_info']['site_name'] ?? ''); ?>" placeholder="VD: Huutien Mods" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-indigo-500">
                  <p class="text-xs text-gray-400 mt-1">Hiển thị ở header và sidebar</p>
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-heading mr-1"></i> Tiêu đề trang (Title)
                  </label>
                  <input type="text" name="site_title" value="<?php echo safeHtml($siteData['site_info']['site_title'] ?? ''); ?>" placeholder="VD: thanhtapcode" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-indigo-500">
                  <p class="text-xs text-gray-400 mt-1">Hiển thị trên tab trình duyệt</p>
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-palette mr-1"></i> Brand - Phần 1 (màu xanh)
                  </label>
                  <input type="text" name="brand_first" value="<?php echo safeHtml($siteData['site_info']['brand_first'] ?? ''); ?>" placeholder="VD: Huutien" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-indigo-500">
                  <p class="text-xs text-gray-400 mt-1">Phần chữ màu xanh trên header</p>
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-palette mr-1"></i> Brand - Phần 2
                  </label>
                  <input type="text" name="brand_second" value="<?php echo safeHtml($siteData['site_info']['brand_second'] ?? ''); ?>" placeholder="VD: Mods" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-indigo-500">
                  <p class="text-xs text-gray-400 mt-1">Phần chữ còn lại trên header</p>
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-copyright mr-1"></i> Footer text
                  </label>
                  <input type="text" name="footer_text" value="<?php echo safeHtml($siteData['site_info']['footer_text'] ?? ''); ?>" placeholder="VD: @thanhtapcode" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-indigo-500">
                  <p class="text-xs text-gray-400 mt-1">Hiển thị ở cuối trang</p>
                </div>
              </div>
            </div>

            <!-- ========== SEO Meta ========== -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
              <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i class="fa-brands fa-searchengin text-green-500"></i>
                SEO & Meta Tags
              </h3>
              <div class="space-y-4">
                <div>
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-paragraph mr-1"></i> Mô tả website (Meta Description)
                  </label>
                  <textarea name="site_description" rows="3" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-indigo-500" placeholder="Mô tả ngắn gọn về website của bạn..."><?php echo safeHtml($siteData['site_info']['site_description'] ?? ''); ?></textarea>
                  <p class="text-xs text-gray-400 mt-1">Hiển thị trong kết quả tìm kiếm, tối đa 160 ký tự</p>
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-keyboard mr-1"></i> Từ khóa SEO (Meta Keywords)
                  </label>
                  <input type="text" name="site_keywords" value="<?php echo safeHtml($siteData['site_info']['site_keywords'] ?? ''); ?>" placeholder="mod game, tai app, android, ios" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-indigo-500">
                  <p class="text-xs text-gray-400 mt-1">Các từ khóa cách nhau bằng dấu phẩy</p>
                </div>
              </div>
            </div>

            <!-- ========== Preview thông tin ========== -->
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-4 border border-indigo-100">
              <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-eye text-indigo-500"></i>
                Xem trước
              </h3>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-gray-500 w-24">Tên website:</span>
                  <span id="previewSiteName" class="font-medium text-gray-800">
                    <?php echo safeHtml($siteData['site_info']['site_name'] ?? 'Huutien Mods'); ?>
                  </span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-gray-500 w-24">Header hiển thị:</span>
                  <span id="previewBrand" class="font-bold">
                    <span class="text-blue-600"><?php echo safeHtml($siteData['site_info']['brand_first'] ?? 'Huutien'); ?></span>
                    <span class="text-gray-800"> <?php echo safeHtml($siteData['site_info']['brand_second'] ?? 'Mods'); ?></span>
                  </span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-gray-500 w-24">Footer text:</span>
                  <span id="previewFooter" class="text-gray-600">
                    <?php echo safeHtml($siteData['site_info']['footer_text'] ?? '@thanhtapcode'); ?>
                  </span>
                </div>
                <div class="flex items-start gap-2">
                  <span class="text-gray-500 w-24">Meta Description:</span>
                  <span id="previewDescription" class="text-gray-600 text-xs italic">
                    <?php echo safeHtml($siteData['site_info']['site_description'] ?? 'Chưa có mô tả'); ?>
                  </span>
                </div>
              </div>
            </div>

            <!-- ========== Nút lưu ========== -->
            <div class="pt-2">
              <button type="submit" name="update_site_info" class="btn-primary px-6 py-2.5 text-white rounded-lg text-sm font-medium">
                <i class="fa-solid fa-save mr-2"></i> Lưu cài đặt
              </button>
            </div>

          </form>
        </div>
      </div>

      <!-- ==================== BACKGROUND SECTION ==================== -->
      <div id="background_section" class="section-card scroll-mt">
        <div class="section-header">
          <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-image text-green-500"></i>
            Background
          </h2>
          <p class="text-xs text-gray-500 mt-1">Quản lý ảnh nền cho website</p>
        </div>
        <div class="section-body">
          <form method="POST" action="" class="space-y-5" enctype="multipart/form-data">

            <!-- ========== Loại background ========== -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
              <label class="block text-sm font-medium text-gray-700 mb-3">
                <i class="fa-solid fa-sliders-h mr-1 text-green-500"></i> Loại background
              </label>
              <div class="flex gap-4 flex-wrap">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="bg_type" value="image" <?php echo'image' === ($siteData['background']['type'] ?? 'image') ? 'checked' : ''; ?> class="w-4 h-4 text-green-500" onchange="toggleBgType('image')">
                  <i class="fa-solid fa-image text-green-500"></i>
                  <span class="text-sm">Hình ảnh</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="bg_type" value="video" <?php echo'video' === ($siteData['background']['type'] ?? '') ? 'checked' : ''; ?> class="w-4 h-4 text-green-500" onchange="toggleBgType('video')">
                  <i class="fa-solid fa-video text-green-500"></i>
                  <span class="text-sm">Video</span>
                </label>
              </div>
            </div>

            <!-- ========== Background Hình ảnh ========== -->
            <div id="bgImageSection" class="bg-gray-50 rounded-xl p-4 border border-gray-200 <?php echo'image' !== ($siteData['background']['type'] ?? 'image') ? 'hidden' : ''; ?>">
              <label class="block text-sm font-medium text-gray-700 mb-3">
                <i class="fa-solid fa-image mr-1 text-blue-500"></i> Ảnh nền
              </label>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Desktop Background -->
                <div>
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-desktop mr-1"></i> Ảnh nền Desktop
                  </label>
                  <div class="flex gap-2">
                    <input type="text" name="bg_url" id="bg_url" value="<?php echo safeHtml($siteData['background']['url'] ?? ''); ?>" placeholder="/uploads/background/desktop.jpg" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 font-mono focus:border-blue-500" oninput="previewDesktopImage()">
                    <input type="file" id="bgDesktopUpload" accept="image/*" class="hidden" onchange="uploadBackgroundImage(this, 'desktop')">
                    <button type="button" onclick="document.getElementById('bgDesktopUpload').click()" class="bg-blue-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-600 transition flex items-center gap-1">
                      <i class="fa-solid fa-upload"></i> Chọn ảnh
                    </button>
                  </div>
                  <div id="bgPreviewDesktop" class="mt-3 rounded-lg overflow-hidden border border-gray-200 bg-white">
                    <?php
                            $desktopBg = $siteData['background']['url'] ?? '';
                            if (!empty($desktopBg)):
                            ?>
                    <div class="relative aspect-video bg-gray-100">
                      <img src="<?php echo safeHtml($desktopBg); ?>" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/800x450?text=No+Image'">
                    </div>
                    <div class="p-2 text-xs text-gray-500 text-center border-t">
                      <i class="fa-regular fa-eye"></i> Preview Desktop
                    </div>
                    <?php else: ?>
                    <div class="relative aspect-video bg-gray-100">
                      <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <i class="fa-solid fa-image text-3xl"></i>
                        <span class="ml-2 text-sm">Chưa có ảnh nền</span>
                      </div>
                    </div>
                    <div class="p-2 text-xs text-gray-500 text-center border-t">
                      <i class="fa-regular fa-eye"></i> Preview Desktop
                    </div>
                    <?php endif; ?>
                  </div>
                  <p class="text-xs text-gray-400 mt-2">
                    <i class="fa-regular fa-lightbulb"></i> Khuyến nghị: 1920x1080px
                  </p>
                </div>

                <!-- Mobile Background -->
                <div>
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-mobile-alt mr-1"></i> Ảnh nền Mobile
                  </label>
                  <div class="flex gap-2">
                    <input type="text" name="bg_mobile_url" id="bg_mobile_url" value="<?php echo safeHtml($siteData['background']['mobile_url'] ?? ''); ?>" placeholder="/uploads/background/mobile.jpg" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 font-mono focus:border-blue-500" oninput="previewMobileImage()">
                    <input type="file" id="bgMobileUpload" accept="image/*" class="hidden" onchange="uploadBackgroundImage(this, 'mobile')">
                    <button type="button" onclick="document.getElementById('bgMobileUpload').click()" class="bg-blue-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-600 transition flex items-center gap-1">
                      <i class="fa-solid fa-upload"></i> Chọn ảnh
                    </button>
                  </div>
                  <div id="bgPreviewMobile" class="mt-3 rounded-lg overflow-hidden border border-gray-200 bg-white">
                    <?php
                            $mobileBg = $siteData['background']['mobile_url'] ?? '';
                            if (!empty($mobileBg)):
                            ?>
                    <div class="relative aspect-[9/16] max-h-48 bg-gray-100 mx-auto">
                      <img src="<?php echo safeHtml($mobileBg); ?>" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/300x533?text=No+Image'">
                    </div>
                    <div class="p-2 text-xs text-gray-500 text-center border-t">
                      <i class="fa-regular fa-eye"></i> Preview Mobile
                    </div>
                    <?php else: ?>
                    <div class="relative aspect-[9/16] max-h-48 bg-gray-100 mx-auto">
                      <div class="w-full h-full flex items-center justify-center text-gray-400 flex-col">
                        <i class="fa-solid fa-mobile-alt text-2xl mb-1"></i>
                        <span class="text-xs">Chưa có ảnh nền mobile</span>
                        <span class="text-xs text-gray-400">(sẽ dùng ảnh desktop)</span>
                      </div>
                    </div>
                    <div class="p-2 text-xs text-gray-500 text-center border-t">
                      <i class="fa-regular fa-eye"></i> Preview Mobile
                    </div>
                    <?php endif; ?>
                  </div>
                  <p class="text-xs text-gray-400 mt-2">
                    <i class="fa-regular fa-lightbulb"></i> Khuyến nghị: 1080x1920px, để trống sẽ dùng ảnh desktop
                  </p>
                </div>
              </div>
            </div>

            <!-- ========== Background Video ========== -->
            <div id="bgVideoSection" class="bg-gray-50 rounded-xl p-4 border border-gray-200 <?php echo'video' !== ($siteData['background']['type'] ?? '') ? 'hidden' : ''; ?>">
              <label class="block text-sm font-medium text-gray-700 mb-3">
                <i class="fa-solid fa-video mr-1 text-blue-500"></i> Video nền
              </label>
              <div>
                <label class="block text-xs text-gray-600 mb-1">URL Video (MP4)</label>
                <div class="flex gap-2">
                  <input type="text" name="bg_video_url" id="bg_video_url" value="<?php echo safeHtml($siteData['background']['video_url'] ?? ''); ?>" placeholder="/uploads/background/video.mp4" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 font-mono focus:border-blue-500" oninput="previewVideo()">
                  <input type="file" id="bgVideoUpload" accept="video/mp4" class="hidden" onchange="uploadVideo(this)">
                  <button type="button" onclick="document.getElementById('bgVideoUpload').click()" class="bg-blue-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-600 transition flex items-center gap-1">
                    <i class="fa-solid fa-upload"></i> Chọn video
                  </button>
                </div>
                <div id="bgVideoPreview" class="mt-3 rounded-lg overflow-hidden border border-gray-200 bg-black <?php echo empty($siteData['background']['video_url']) ? 'hidden' : ''; ?>">
                  <video id="videoPreviewPlayer" class="w-full max-h-64 object-contain" controls muted loop>
                    <source src="<?php echo safeHtml($siteData['background']['video_url'] ?? ''); ?>" type="video/mp4">
                  </video>
                  <div class="p-2 text-xs text-gray-400 text-center border-t bg-gray-50">
                    <i class="fa-solid fa-play-circle"></i> Preview video
                  </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">
                  <i class="fa-regular fa-lightbulb"></i> Khuyến nghị: Video nhẹ, dưới 10MB, định dạng MP4
                </p>
              </div>
            </div>

            <!-- ========== Ảnh gợi ý ========== -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
              <label class="block text-sm font-medium text-gray-700 mb-3">
                <i class="fa-solid fa-images mr-1 text-green-500"></i> Ảnh gợi ý
              </label>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="suggested-bg cursor-pointer rounded-lg overflow-hidden border-2 border-transparent hover:border-blue-500 transition" onclick="setSuggestedImage('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920')">
                  <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400" class="w-full h-20 object-cover">
                  <p class="text-xs text-center p-1 bg-gray-100">Biển</p>
                </div>
                <div class="suggested-bg cursor-pointer rounded-lg overflow-hidden border-2 border-transparent hover:border-blue-500 transition" onclick="setSuggestedImage('https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?w=1920')">
                  <img src="https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?w=400" class="w-full h-20 object-cover">
                  <p class="text-xs text-center p-1 bg-gray-100">Rừng</p>
                </div>
                <div class="suggested-bg cursor-pointer rounded-lg overflow-hidden border-2 border-transparent hover:border-blue-500 transition" onclick="setSuggestedImage('https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=1920')">
                  <img src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=400" class="w-full h-20 object-cover">
                  <p class="text-xs text-center p-1 bg-gray-100">Núi</p>
                </div>
                <div class="suggested-bg cursor-pointer rounded-lg overflow-hidden border-2 border-transparent hover:border-blue-500 transition" onclick="setSuggestedImage('https://images.unsplash.com/photo-1472214103451-9374bd1c798e?w=1920')">
                  <img src="https://images.unsplash.com/photo-1472214103451-9374bd1c798e?w=400" class="w-full h-20 object-cover">
                  <p class="text-xs text-center p-1 bg-gray-100">Đồng quê</p>
                </div>
              </div>
            </div>

            <!-- ========== Nút lưu ========== -->
            <div class="pt-2">
              <button type="submit" name="update_background" class="btn-primary px-6 py-2.5 text-white rounded-lg text-sm font-medium">
                <i class="fa-solid fa-save mr-2"></i> Lưu background
              </button>
            </div>

          </form>
        </div>
      </div>

      <!-- iframe để upload ảnh không reload trang -->
      <iframe name="uploadFrame" style="display:none;" onload="handleUploadResponse()"></iframe>

      <!-- ==================== PROFILE SECTION ==================== -->
      <div id="profile" class="section-card scroll-mt">
        <div class="section-header">
          <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-user text-blue-500"></i>
            Hồ sơ
          </h2>
          <p class="text-xs text-gray-500 mt-1">Quản lý thông tin cá nhân hiển thị trên trang chủ</p>
        </div>
        <div class="section-body">
          <form method="POST" action="" class="space-y-4" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Cột trái: Avatar -->
              <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <div class="text-center">
                  <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fa-solid fa-image mr-1 text-blue-500"></i> Avatar
                  </label>
                  <div class="relative inline-block">
                    <div id="avatarPreview" class="w-32 h-32 rounded-full mx-auto overflow-hidden border-4 border-blue-500 shadow-lg bg-white">
                      <?php if (!empty($siteData['profile']['avatar'])): ?>
                      <img src="<?php echo safeHtml($siteData['profile']['avatar']); ?>" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/128x128?text=Avatar'">
                      <?php else: ?>
                      <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <i class="fa-solid fa-user text-4xl text-gray-400"></i>
                      </div>
                      <?php endif; ?>
                    </div>
                    <button type="button" onclick="document.getElementById('avatarUpload').click()" class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full w-8 h-8 flex items-center justify-center shadow-md hover:bg-blue-600 transition">
                      <i class="fa-solid fa-camera text-xs"></i>
                    </button>
                    <input type="file" id="avatarUpload" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                  </div>
                  <p class="text-xs text-gray-400 mt-3">Click vào camera để đổi avatar</p>
                  <input type="hidden" name="profile_avatar" id="profile_avatar_input" value="<?php echo safeHtml($siteData['profile']['avatar'] ?? ''); ?>">
                </div>
              </div>

              <!-- Cột phải: Thông tin -->
              <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                  <i class="fa-solid fa-info-circle mr-1 text-blue-500"></i> Thông tin cơ bản
                </label>
                <div class="space-y-3">
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Tên hiển thị</label>
                    <div class="flex items-center gap-2">
                      <i class="fa-solid fa-tag text-gray-400"></i>
                      <input type="text" name="profile_name" value="<?php echo safeHtml($siteData['profile']['name'] ?? ''); ?>" placeholder="VD: @thanhtapcode" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Tên hiển thị lớn ở giữa trang</p>
                  </div>
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Tiêu đề</label>
                    <div class="flex items-center gap-2">
                      <i class="fa-solid fa-heading text-gray-400"></i>
                      <input type="text" name="profile_title" value="<?php echo safeHtml($siteData['profile']['title'] ?? ''); ?>" placeholder="VD: thành tập code" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Dòng chữ nhỏ phía trên tên</p>
                  </div>
                  <div>
                    <label class="block text-xs text-gray-600 mb-1">Phụ đề</label>
                    <div class="flex items-center gap-2">
                      <i class="fa-solid fa-quote-right text-gray-400"></i>
                      <input type="text" name="profile_subtitle" value="<?php echo safeHtml($siteData['profile']['subtitle'] ?? ''); ?>" placeholder="VD: mã nguồn ngon" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Dòng chữ phía dưới tên</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Preview thông tin -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 border border-blue-100">
              <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-eye text-blue-500"></i> Xem trước
              </h3>
              <div class="flex items-center gap-4 flex-wrap">
                <div class="w-16 h-16 rounded-full bg-white border-2 border-blue-500 overflow-hidden shadow">
                  <img id="liveAvatarPreview" src="<?php echo safeHtml($siteData['profile']['avatar'] ?? ''); ?>" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/64x64?text=Avatar'">
                </div>
                <div>
                  <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">Tên:</span>
                    <span id="liveNamePreview" class="font-bold text-yellow-600"><?php echo safeHtml($siteData['profile']['name'] ?? '@thanhtapcode'); ?></span>
                  </div>
                  <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-gray-400">Tiêu đề:</span>
                    <span id="liveTitlePreview" class="text-xs text-blue-500"><?php echo safeHtml($siteData['profile']['title'] ?? ''); ?></span>
                  </div>
                  <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs text-gray-400">Phụ đề:</span>
                    <span id="liveSubtitlePreview" class="text-xs text-cyan-500"><?php echo safeHtml($siteData['profile']['subtitle'] ?? ''); ?></span>
                  </div>
                </div>
              </div>
            </div>

            <div class="pt-2">
              <button type="submit" name="update_profile" class="btn-primary px-6 py-2.5 text-white rounded-lg text-sm font-medium">
                <i class="fa-solid fa-save mr-2"></i> Lưu hồ sơ
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Upload Avatar Modal (nếu muốn upload file) -->
      <div id="uploadAvatarModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4" onclick="closeAvatarModal(event)">
        <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl" onclick="event.stopPropagation()">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">
              <i class="fa-solid fa-upload text-blue-500 mr-2"></i>
              Tải lên avatar
            </h3>
            <button onclick="closeAvatarModal()" class="text-gray-400 hover:text-gray-600">
              <i class="fa-solid fa-times text-xl"></i>
            </button>
          </div>
          <div class="text-center">
            <img id="uploadPreview" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-blue-500 mb-4" src="https://placehold.co/128x128?text=Preview">
            <input type="file" id="avatarFileInput" accept="image/*" class="hidden" onchange="previewUploadAvatar(this)">
            <button type="button" onclick="document.getElementById('avatarFileInput').click()" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-600 transition">
              <i class="fa-solid fa-folder-open mr-2"></i> Chọn ảnh
            </button>
            <div class="mt-4 flex gap-2">
              <button type="button" onclick="closeAvatarModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">Hủy</button>
              <button type="button" onclick="uploadAvatar()" class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg text-sm">Lưu avatar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- ==================== SIDEBAR MENU SECTION ==================== -->
      <div id="sidebar_menu" class="section-card scroll-mt">
        <div class="section-header">
          <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-bars-staggered text-purple-500"></i>
            Sidebar Menu
          </h2>
          <p class="text-xs text-gray-500 mt-1">Quản lý menu hiển thị bên trái website</p>
        </div>
        <div class="section-body">
          <form method="POST" action="" class="space-y-4">
            <div id="sidebar-menu-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <?php
                // Kiểm tra và hiển thị tất cả các mục trong sidebar_menu
                if (!empty($siteData['sidebar_menu']) && is_array($siteData['sidebar_menu'])):
                    foreach ($siteData['sidebar_menu'] as $menu_index => $menu):
                ?>
              <div class="sidebar-menu-row bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Tiêu đề menu</label>
                  <input type="text" name="menu_title[]" value="<?php echo safeHtml($menu['title'] ?? ''); ?>" placeholder="VD: Trang chủ" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Icon class</label>
                  <div class="flex gap-2">
                    <input type="text" name="menu_icon[]" value="<?php echo safeHtml($menu['icon'] ?? ''); ?>" placeholder="VD: fa-solid fa-home" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-300">
                    <button type="button" onclick="openMenuIconPicker(this)" class="bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">
                      <i class="fa-solid fa-icons"></i>
                    </button>
                  </div>
                  <div class="mt-2 flex gap-2 flex-wrap">
                    <span class="text-xs text-gray-500">Gợi ý:</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-home')"><i class="fa-solid fa-home mr-1"></i>home</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-user-shield')"><i class="fa-solid fa-user-shield mr-1"></i>admin</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-gear')"><i class="fa-solid fa-gear mr-1"></i>cài đặt</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-question-circle')"><i class="fa-solid fa-question-circle mr-1"></i>hỗ trợ</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-brands fa-telegram')"><i class="fa-brands fa-telegram mr-1"></i>telegram</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-envelope')"><i class="fa-solid fa-envelope mr-1"></i>liên hệ</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-folder')"><i class="fa-solid fa-folder mr-1"></i>danh mục</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-music')"><i class="fa-solid fa-music mr-1"></i>nhạc</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-dashboard')"><i class="fa-solid fa-dashboard mr-1"></i>dashboard</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-chart-line')"><i class="fa-solid fa-chart-line mr-1"></i>thống kê</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-users')"><i class="fa-solid fa-users mr-1"></i>người dùng</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-cart-shopping')"><i class="fa-solid fa-cart-shopping mr-1"></i>giỏ hàng</span>
                  </div>
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Đường dẫn URL</label>
                  <input type="text" name="menu_url[]" value="<?php echo safeHtml($menu['url'] ?? ''); ?>" placeholder="VD: index.php" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div class="text-xs text-gray-500 mt-2 pt-2 border-t border-gray-200">
                  <i class="fa-solid fa-eye mr-1"></i> Preview:
                  <i class="<?php echo safeHtml($menu['icon'] ?? 'fa-solid fa-link'); ?> mr-1"></i>
                  <span class="preview-title"><?php echo safeHtml($menu['title'] ?? 'Menu'); ?></span>
                  <span class="text-gray-400"> → </span>
                  <span class="preview-url text-blue-500 truncate"><?php echo safeHtml($menu['url'] ?? '#'); ?></span>
                </div>
                <button type="button" onclick="removeSidebarRow(this)" class="text-red-500 text-sm mt-3 w-full text-center py-1 border-t border-gray-200 pt-2">
                  <i class="fa-solid fa-trash-alt mr-1"></i> Xóa menu
                </button>
              </div>
              <?php
                    endforeach;
                else:
                ?>
              <!-- Mặc định hiển thị 2 row nếu chưa có dữ liệu -->
              <div class="sidebar-menu-row bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Tiêu đề menu</label>
                  <input type="text" name="menu_title[]" placeholder="VD: Trang chủ" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Icon class</label>
                  <div class="flex gap-2">
                    <input type="text" name="menu_icon[]" placeholder="VD: fa-solid fa-home" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-300">
                    <button type="button" onclick="openMenuIconPicker(this)" class="bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">
                      <i class="fa-solid fa-icons"></i>
                    </button>
                  </div>
                  <div class="mt-2 flex gap-2 flex-wrap">
                    <span class="text-xs text-gray-500">Gợi ý:</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-home')"><i class="fa-solid fa-home mr-1"></i>home</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-user-shield')"><i class="fa-solid fa-user-shield mr-1"></i>admin</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-gear')"><i class="fa-solid fa-gear mr-1"></i>cài đặt</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-question-circle')"><i class="fa-solid fa-question-circle mr-1"></i>hỗ trợ</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-brands fa-telegram')"><i class="fa-brands fa-telegram mr-1"></i>telegram</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-envelope')"><i class="fa-solid fa-envelope mr-1"></i>liên hệ</span>
                  </div>
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Đường dẫn URL</label>
                  <input type="text" name="menu_url[]" placeholder="VD: index.php" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div class="text-xs text-gray-500 mt-2 pt-2 border-t border-gray-200">
                  <i class="fa-solid fa-eye mr-1"></i> Preview:
                  <i class="fa-solid fa-link mr-1"></i>
                  <span class="preview-title">Menu mới</span>
                  <span class="text-gray-400"> → </span>
                  <span class="preview-url">#</span>
                </div>
                <button type="button" onclick="removeSidebarRow(this)" class="text-red-500 text-sm mt-3 w-full text-center py-1 border-t border-gray-200 pt-2">
                  <i class="fa-solid fa-trash-alt mr-1"></i> Xóa menu
                </button>
              </div>
              <div class="sidebar-menu-row bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Tiêu đề menu</label>
                  <input type="text" name="menu_title[]" placeholder="VD: Admin Panel" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Icon class</label>
                  <div class="flex gap-2">
                    <input type="text" name="menu_icon[]" placeholder="VD: fa-solid fa-user-shield" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-300">
                    <button type="button" onclick="openMenuIconPicker(this)" class="bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">
                      <i class="fa-solid fa-icons"></i>
                    </button>
                  </div>
                  <div class="mt-2 flex gap-2 flex-wrap">
                    <span class="text-xs text-gray-500">Gợi ý:</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-home')"><i class="fa-solid fa-home mr-1"></i>home</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-user-shield')"><i class="fa-solid fa-user-shield mr-1"></i>admin</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-gear')"><i class="fa-solid fa-gear mr-1"></i>cài đặt</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-question-circle')"><i class="fa-solid fa-question-circle mr-1"></i>hỗ trợ</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-brands fa-telegram')"><i class="fa-brands fa-telegram mr-1"></i>telegram</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-envelope')"><i class="fa-solid fa-envelope mr-1"></i>liên hệ</span>
                  </div>
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Đường dẫn URL</label>
                  <input type="text" name="menu_url[]" placeholder="VD: LuanOriCpanel.php" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div class="text-xs text-gray-500 mt-2 pt-2 border-t border-gray-200">
                  <i class="fa-solid fa-eye mr-1"></i> Preview:
                  <i class="fa-solid fa-link mr-1"></i>
                  <span class="preview-title">Menu mới</span>
                  <span class="text-gray-400"> → </span>
                  <span class="preview-url">#</span>
                </div>
                <button type="button" onclick="removeSidebarRow(this)" class="text-red-500 text-sm mt-3 w-full text-center py-1 border-t border-gray-200 pt-2">
                  <i class="fa-solid fa-trash-alt mr-1"></i> Xóa menu
                </button>
              </div>
              <?php endif; ?>
            </div>

            <button type="button" onclick="addSidebarRow()" class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1 mt-2">
              <i class="fa-solid fa-plus-circle"></i> + Thêm mục menu
            </button>

            <div class="pt-4 border-t border-gray-200 mt-4">
              <button type="submit" name="update_sidebar" class="btn-primary px-6 py-2.5 text-white rounded-lg text-sm font-medium">
                <i class="fa-solid fa-save mr-2"></i> Lưu Sidebar Menu
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- ==================== CATEGORIES SECTION ==================== -->
      <div id="categories" class="section-card scroll-mt">
        <div class="section-header">
          <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-folder-tree text-purple-500"></i>
            Danh mục & Mục
          </h2>
          <p class="text-xs text-gray-500 mt-1">Quản lý danh mục và các mục bên trong</p>
        </div>
        <div class="section-body">

          <!-- Form thêm danh mục mới -->
          <div class="bg-gradient-to-r from-purple-50 to-white rounded-xl p-4 mb-6 border border-purple-100">
            <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
              <i class="fa-solid fa-plus-circle text-green-500"></i>
              Thêm danh mục mới
            </h3>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <label class="block text-xs text-gray-600 mb-1">Tên danh mục *</label>
                <input type="text" name="category_name" required placeholder="VD: FILE, Esign, Ksign..." class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-purple-500">
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">Tiêu đề mục *</label>
                <input type="text" name="item_title" required placeholder="Tiêu đề mục" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-purple-500">
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">URL hình ảnh</label>
                <input type="url" name="item_image" id="item_image" placeholder="https://..." class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-purple-500" onchange="previewImage('item_image', 'item_preview')">
                <div id="item_preview" class="hidden mt-1">
                  <img id="item_preview_img" class="w-12 h-12 rounded-lg object-cover border">
                </div>
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">Đường dẫn URL *</label>
                <input type="text" name="item_url" required placeholder="https:// hoặc đường dẫn file" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-purple-500">
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">Badge text</label>
                <input type="text" name="item_badge" placeholder="NEW, UPDATE, HOT..." class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-purple-500">
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">Màu badge</label>
                <input type="color" name="item_badge_color" value="#22c55e" class="w-full h-10 rounded-lg border border-gray-200 cursor-pointer">
              </div>
              <div class="md:col-span-2">
                <button type="submit" name="add_category_item" class="btn-success px-4 py-2 text-white rounded-lg text-sm">
                  <i class="fa-solid fa-plus mr-1"></i> Thêm mục vào danh mục
                </button>
              </div>
            </form>
          </div>

          <!-- Danh sách các danh mục dạng thẻ -->
          <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-blue-500"></i>
            Danh sách danh mục
          </h3>

          <?php if (empty($siteData['categories'])): ?>
          <div class="text-center py-8 bg-gray-50 rounded-xl">
            <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-2"></i>
            <p class="text-gray-500 text-sm">Chưa có danh mục nào. Hãy thêm mục mới bên trên.</p>
          </div>
          <?php else: ?>
          <div class="space-y-6">
            <?php foreach ($siteData['categories'] as $catName => $items): ?>
            <div class="category-group bg-gray-50 rounded-xl overflow-hidden border border-gray-200">
              <!-- Header danh mục -->
              <div class="bg-gradient-to-r from-purple-100 to-purple-50 px-4 py-3 flex justify-between items-center flex-wrap gap-2">
                <div class="flex items-center gap-2">
                  <i class="fa-solid fa-folder text-purple-600 text-lg"></i>
                  <h4 class="font-bold text-purple-700"><?php echo safeHtml($catName); ?></h4>
                  <span class="bg-purple-200 text-purple-700 text-xs px-2 py-0.5 rounded-full"><?php echo count($items); ?> mục</span>
                </div>
                <div class="flex gap-2">
                  <button type="button" onclick="showAddItemToCategory('<?php echo addslashes($catName); ?>')" class="text-xs bg-green-100 text-green-600 px-3 py-1 rounded-lg hover:bg-green-200 transition">
                    <i class="fa-solid fa-plus mr-1"></i> Thêm mục
                  </button>
                  <button type="button" onclick="deleteCategory('<?php echo addslashes($catName); ?>')" class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded-lg hover:bg-red-200 transition">
                    <i class="fa-solid fa-trash-alt mr-1"></i> Xóa danh mục
                  </button>
                </div>
              </div>

              <!-- Danh sách mục trong danh mục - dạng grid -->
              <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3">
                  <?php foreach ($items as $idx => $item): ?>
                  <div class="category-item bg-white rounded-lg p-3 border border-gray-200 hover:shadow-md transition">
                    <div class="flex items-start gap-3">
                      <!-- Ảnh -->
                      <div class="flex-shrink-0">
                        <?php if (!empty($item['image'])): ?>
                        <img src="<?php echo safeHtml($item['image']); ?>" class="w-14 h-14 rounded-lg object-cover border" onerror="this.src='https://placehold.co/56x56?text=No+Image'">
                        <?php else: ?>
                        <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center border">
                          <i class="fa-solid fa-image text-gray-400 text-xl"></i>
                        </div>
                        <?php endif; ?>
                      </div>

                      <!-- Nội dung -->
                      <div class="flex-1">
                        <div class="flex items-start justify-between">
                          <div>
                            <p class="font-medium text-gray-800 text-sm"><?php echo safeHtml($item['title'] ?? ''); ?></p>
                            <p class="text-xs text-gray-500 truncate max-w-[200px] md:max-w-[300px]">
                              <?php echo safeHtml($item['url'] ?? ''); ?>
                            </p>
                            <?php if (!empty($item['badge'])): ?>
                            <span class="inline-block text-xs px-2 py-0.5 rounded-full mt-1" style="background: <?php echo safeHtml($item['badge_color'] ?? '#22c55e'); ?>20; color: <?php echo safeHtml($item['badge_color'] ?? '#22c55e'); ?>">
                              <?php echo safeHtml($item['badge']); ?>
                            </span>
                            <?php endif; ?>
                          </div>
                          <form method="POST" onsubmit="return confirm('Xóa mục \'<?php echo addslashes($item['title'] ?? ''); ?>\' này?')">
                            <input type="hidden" name="delete_category" value="<?php echo safeHtml($catName); ?>">
                            <input type="hidden" name="delete_index" value="<?php echo $idx; ?>">
                            <button type="submit" name="delete_item" class="text-red-500 hover:text-red-700 p-1 transition">
                              <i class="fa-solid fa-trash-alt"></i>
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Modal thêm mục vào danh mục (ẩn) -->
      <div id="addItemModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4" onclick="closeModal(event)">
        <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl" onclick="event.stopPropagation()">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">
              <i class="fa-solid fa-plus-circle text-green-500 mr-2"></i>
              Thêm mục vào danh mục: <span id="modalCategoryName" class="text-purple-600"></span>
            </h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
              <i class="fa-solid fa-times text-xl"></i>
            </button>
          </div>
          <form method="POST" id="addItemForm">
            <input type="hidden" name="category_name" id="modalCategoryInput">
            <div class="space-y-3">
              <div>
                <label class="block text-xs text-gray-600 mb-1">Tiêu đề mục *</label>
                <input type="text" name="item_title" required placeholder="Tiêu đề mục" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200">
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">URL hình ảnh</label>
                <input type="url" name="item_image" id="modal_item_image" placeholder="https://..." class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200" onchange="previewModalImage()">
                <div id="modal_image_preview" class="hidden mt-1">
                  <img id="modal_preview_img" class="w-12 h-12 rounded-lg object-cover border">
                </div>
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">Đường dẫn URL *</label>
                <input type="text" name="item_url" required placeholder="https:// hoặc đường dẫn file" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200">
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">Badge text</label>
                <input type="text" name="item_badge" placeholder="NEW, UPDATE, HOT..." class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200">
              </div>
              <div>
                <label class="block text-xs text-gray-600 mb-1">Màu badge</label>
                <input type="color" name="item_badge_color" value="#22c55e" class="w-full h-10 rounded-lg border border-gray-200 cursor-pointer">
              </div>
            </div>
            <div class="mt-4 flex gap-2">
              <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">Hủy</button>
              <button type="submit" name="add_category_item" class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg text-sm">Thêm mục</button>
            </div>
          </form>
        </div>
      </div>

      <!-- ==================== SOCIAL LINKS SECTION ==================== -->
      <div id="social" class="section-card scroll-mt">
        <div class="section-header">
          <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-share-alt text-pink-500"></i>
            Liên kết mạng xã hội
          </h2>
          <p class="text-xs text-gray-500 mt-1">Quản lý các liên kết hiển thị dưới avatar</p>
        </div>
        <div class="section-body">
          <form method="POST" action="" class="space-y-4">
            <div id="social-links-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <?php if (empty($siteData['social_links'])): ?>
              <!-- Hàng 1 - Form 1 và 2 -->
              <div class="social-link-row bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Tên mạng xã hội</label>
                  <input type="text" name="social_name[]" placeholder="VD: Facebook" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">URL liên kết</label>
                  <input type="url" name="social_url[]" placeholder="https://..." class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Icon class</label>
                  <div class="flex gap-2">
                    <input type="text" name="social_icon[]" placeholder="fab fa-facebook" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-300">
                    <button type="button" onclick="openIconPicker(this)" class="bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">
                      <i class="fa-solid fa-icons"></i>
                    </button>
                  </div>
                  <div class="mt-2 flex gap-2 flex-wrap">
                    <span class="text-xs text-gray-500">Gợi ý:</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-facebook')"><i class="fab fa-facebook mr-1"></i>fb</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-telegram')"><i class="fab fa-telegram mr-1"></i>tele</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-tiktok')"><i class="fab fa-tiktok mr-1"></i>tt</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-discord')"><i class="fab fa-discord mr-1"></i>dc</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-instagram')"><i class="fab fa-instagram mr-1"></i>ig</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-youtube')"><i class="fab fa-youtube mr-1"></i>yt</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-twitter')"><i class="fab fa-twitter mr-1"></i>x</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-github')"><i class="fab fa-github mr-1"></i>git</span>
                  </div>
                </div>
                <button type="button" onclick="removeSocialRow(this)" class="text-red-500 text-sm mt-3 w-full text-center py-1 border-t border-gray-200 pt-2">
                  <i class="fa-solid fa-trash-alt mr-1"></i> Xóa
                </button>
              </div>
              <div class="social-link-row bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Tên mạng xã hội</label>
                  <input type="text" name="social_name[]" placeholder="VD: Telegram" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">URL liên kết</label>
                  <input type="url" name="social_url[]" placeholder="https://..." class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Icon class</label>
                  <div class="flex gap-2">
                    <input type="text" name="social_icon[]" placeholder="fab fa-telegram" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-300">
                    <button type="button" onclick="openIconPicker(this)" class="bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">
                      <i class="fa-solid fa-icons"></i>
                    </button>
                  </div>
                  <div class="mt-2 flex gap-2 flex-wrap">
                    <span class="text-xs text-gray-500">Gợi ý:</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-facebook')"><i class="fab fa-facebook mr-1"></i>fb</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-telegram')"><i class="fab fa-telegram mr-1"></i>tele</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-tiktok')"><i class="fab fa-tiktok mr-1"></i>tt</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-discord')"><i class="fab fa-discord mr-1"></i>dc</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-instagram')"><i class="fab fa-instagram mr-1"></i>ig</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-youtube')"><i class="fab fa-youtube mr-1"></i>yt</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-twitter')"><i class="fab fa-twitter mr-1"></i>x</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-github')"><i class="fab fa-github mr-1"></i>git</span>
                  </div>
                </div>
                <button type="button" onclick="removeSocialRow(this)" class="text-red-500 text-sm mt-3 w-full text-center py-1 border-t border-gray-200 pt-2">
                  <i class="fa-solid fa-trash-alt mr-1"></i> Xóa
                </button>
              </div>
              <?php else: ?>
              <?php
                    $social_count = count($siteData['social_links']);
                    $social_index = 0;
                    foreach ($siteData['social_links'] as $social):
                    ?>
              <div class="social-link-row bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">Tên mạng xã hội</label>
                  <input type="text" name="social_name[]" value="<?php echo safeHtml($social['name'] ?? ''); ?>" placeholder="VD: Facebook" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">URL liên kết</label>
                  <input type="url" name="social_url[]" value="<?php echo safeHtml($social['url'] ?? ''); ?>" placeholder="https://..." class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
                </div>
                <div>
                  <label class="block text-xs text-gray-600 mb-1">Icon class</label>
                  <div class="flex gap-2">
                    <input type="text" name="social_icon[]" value="<?php echo safeHtml($social['icon'] ?? ''); ?>" placeholder="fab fa-facebook" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-300">
                    <button type="button" onclick="openIconPicker(this)" class="bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">
                      <i class="fa-solid fa-icons"></i>
                    </button>
                  </div>
                  <div class="mt-2 flex gap-2 flex-wrap">
                    <span class="text-xs text-gray-500">Gợi ý:</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-facebook')"><i class="fab fa-facebook mr-1"></i>fb</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-telegram')"><i class="fab fa-telegram mr-1"></i>tele</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-tiktok')"><i class="fab fa-tiktok mr-1"></i>tt</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-discord')"><i class="fab fa-discord mr-1"></i>dc</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-instagram')"><i class="fab fa-instagram mr-1"></i>ig</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-youtube')"><i class="fab fa-youtube mr-1"></i>yt</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-twitter')"><i class="fab fa-twitter mr-1"></i>x</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-github')"><i class="fab fa-github mr-1"></i>git</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-linkedin')"><i class="fab fa-linkedin mr-1"></i>in</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-pinterest')"><i class="fab fa-pinterest mr-1"></i>pin</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-snapchat')"><i class="fab fa-snapchat mr-1"></i>snap</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-reddit')"><i class="fab fa-reddit mr-1"></i>reddit</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-whatsapp')"><i class="fab fa-whatsapp mr-1"></i>wa</span>
                  </div>
                </div>
                <button type="button" onclick="removeSocialRow(this)" class="text-red-500 text-sm mt-3 w-full text-center py-1 border-t border-gray-200 pt-2">
                  <i class="fa-solid fa-trash-alt mr-1"></i> Xóa
                </button>
              </div>
              <?php
                    endforeach;
                    ?>
              <?php endif; ?>
            </div>

            <button type="button" onclick="addSocialRow()" class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1 mt-2">
              <i class="fa-solid fa-plus-circle"></i> + Thêm liên kết
            </button>

            <div class="pt-4 border-t border-gray-200 mt-4">
              <button type="submit" name="update_social" class="btn-primary px-6 py-2.5 text-white rounded-lg text-sm font-medium">
                <i class="fa-solid fa-save mr-2"></i> Lưu liên kết
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- ==================== MUSIC SECTION ==================== -->
      <div id="music" class="section-card scroll-mt">
        <div class="section-header">
          <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-music text-green-500"></i>
            Âm nhạc
          </h2>
          <p class="text-xs text-gray-500 mt-1">Quản lý danh sách bài hát phát nền</p>
        </div>
        <div class="section-body">
          <form method="POST" action="" class="space-y-4">
            <!-- Nút thêm bài hát mới -->
            <button type="button" onclick="addMusicCard()" class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1 mb-4 px-3 py-2 bg-blue-50 rounded-lg">
              <i class="fa-solid fa-plus-circle"></i> + Thêm bài hát mới
            </button>

            <!-- Danh sách bài hát dạng thẻ grid -->
            <div id="music-list-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
              <?php
                $music_playlist = $siteData['music']['playlist'] ?? [];
                $music_song_names = $siteData['music']['song_names'] ?? [];

                if (!empty($music_playlist) && count($music_playlist) > 0):
                    foreach ($music_playlist as $index => $playlist_url):
                        $song_name = $music_song_names[$index] ?? 'Bài hát '.($index + 1);
                ?>
              <div class="music-card bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                  <div class="flex items-center gap-2">
                    <span class="bg-green-100 text-green-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"><?php echo $index + 1; ?></span>
                    <span class="text-xs text-gray-400">Bài hát</span>
                  </div>
                  <button type="button" onclick="removeMusicCard(this)" class="text-red-500 hover:text-red-700">
                    <i class="fa-solid fa-trash-alt"></i>
                  </button>
                </div>
                <div class="mb-3">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-heading mr-1"></i> Tên bài hát
                  </label>
                  <input type="text" name="song_names[]" value="<?php echo safeHtml($song_name); ?>" placeholder="VD: Cảm Ơn Em Remix" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-200">
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-link mr-1"></i> Đường dẫn file
                  </label>
                  <div class="flex gap-2">
                    <input type="text" name="playlist[]" value="<?php echo safeHtml($playlist_url); ?>" placeholder="/music/tenbaihat.mp3" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-200">
                    <button type="button" onclick="playMusicPreview(this)" class="bg-green-100 text-green-600 px-3 py-2 rounded-lg text-sm hover:bg-green-200 transition">
                      <i class="fa-solid fa-play"></i>
                    </button>
                  </div>
                  <p class="text-xs text-gray-400 mt-1">
                    <i class="fa-regular fa-folder-open mr-1"></i> Đặt file .mp3 trong thư mục /music/
                  </p>
                </div>
              </div>
              <?php
                    endforeach;
                else:
                ?>
              <div class="music-card bg-gray-50 rounded-xl p-4 border border-gray-200">
                <div class="flex justify-between items-start mb-3">
                  <div class="flex items-center gap-2">
                    <span class="bg-green-100 text-green-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                    <span class="text-xs text-gray-400">Bài hát</span>
                  </div>
                  <button type="button" onclick="removeMusicCard(this)" class="text-red-500 hover:text-red-700">
                    <i class="fa-solid fa-trash-alt"></i>
                  </button>
                </div>
                <div class="mb-3">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-heading mr-1"></i> Tên bài hát
                  </label>
                  <input type="text" name="song_names[]" placeholder="VD: Cảm Ơn Em Remix" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-link mr-1"></i> Đường dẫn file
                  </label>
                  <div class="flex gap-2">
                    <input type="text" name="playlist[]" placeholder="/music/song1.mp3" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 font-mono focus:border-blue-500">
                    <button type="button" onclick="playMusicPreview(this)" class="bg-green-100 text-green-600 px-3 py-2 rounded-lg text-sm hover:bg-green-200 transition">
                      <i class="fa-solid fa-play"></i>
                    </button>
                  </div>
                  <p class="text-xs text-gray-400 mt-1">
                    <i class="fa-regular fa-folder-open mr-1"></i> Đặt file .mp3 trong thư mục /music/
                  </p>
                </div>
              </div>
              <?php endif; ?>
            </div>

            <!-- Audio preview player -->
            <audio id="musicPreview" style="display: none;"></audio>

            <div class="pt-4 border-t border-gray-200 mt-4">
              <button type="submit" name="update_music" class="btn-primary px-6 py-2.5 text-white rounded-lg text-sm font-medium">
                <i class="fa-solid fa-save mr-2"></i> Lưu danh sách nhạc
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- ==================== NAVBAR CARDS SECTION ==================== -->
      <div id="navbar" class="section-card scroll-mt">
        <div class="section-header">
          <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-bars text-orange-500"></i>
            Navbar Cards
          </h2>
          <p class="text-xs text-gray-500 mt-1">Quản lý các card hiển thị phía dưới avatar</p>
        </div>
        <div class="section-body">
          <form method="POST" action="" class="space-y-4">
            <!-- Nút thêm card mới -->
            <button type="button" onclick="addNavbarCard()" class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1 mb-4 px-3 py-2 bg-blue-50 rounded-lg">
              <i class="fa-solid fa-plus-circle"></i> + Thêm Card mới
            </button>

            <!-- Danh sách Navbar Cards dạng thẻ grid -->
            <div id="navbar-cards-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <?php
                $navbar_cards = $siteData['navbar_cards'] ?? [];

                if (!empty($navbar_cards) && count($navbar_cards) > 0):
                    foreach ($navbar_cards as $index => $card):
                ?>
              <div class="navbar-card bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                  <div class="flex items-center gap-2">
                    <span class="bg-orange-100 text-orange-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"><?php echo $index + 1; ?></span>
                    <span class="text-xs text-gray-400">Navbar Card</span>
                  </div>
                  <button type="button" onclick="removeNavbarCard(this)" class="text-red-500 hover:text-red-700">
                    <i class="fa-solid fa-trash-alt"></i>
                  </button>
                </div>
                <div class="mb-3">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-heading mr-1"></i> Tiêu đề Card
                  </label>
                  <input type="text" name="navbar_title_<?php echo $index; ?>" value="<?php echo safeHtml($card['title'] ?? ''); ?>" placeholder="VD: thanhtapcode" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
                </div>
                <div class="mb-3">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-tag mr-1"></i> Phụ đề
                  </label>
                  <input type="text" name="navbar_subtitle_<?php echo $index; ?>" value="<?php echo safeHtml($card['subtitle'] ?? ''); ?>" placeholder="VD: t.me" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
                </div>
                <div class="mb-3">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-link mr-1"></i> Đường dẫn URL
                  </label>
                  <input type="text" name="navbar_url_<?php echo $index; ?>" value="<?php echo safeHtml($card['url'] ?? ''); ?>" placeholder="https://t.me/..." class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-icons mr-1"></i> Icon
                  </label>
                  <div class="flex gap-2">
                    <input type="text" name="navbar_icon_<?php echo $index; ?>" value="<?php echo safeHtml($card['icon'] ?? 'fa-regular fa-thumbs-up'); ?>" placeholder="fa-regular fa-thumbs-up" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 font-mono focus:border-blue-500">
                    <button type="button" onclick="openNavbarIconPicker(this)" class="bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">
                      <i class="fa-solid fa-icons"></i>
                    </button>
                  </div>
                  <div class="mt-2 flex gap-2 flex-wrap">
                    <span class="text-xs text-gray-500">Gợi ý:</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-regular fa-thumbs-up')"><i class="fa-regular fa-thumbs-up mr-1"></i>thích</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-qrcode')"><i class="fa-solid fa-qrcode mr-1"></i>qr</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-download')"><i class="fa-solid fa-download mr-1"></i>tải</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-cloud-arrow-down')"><i class="fa-solid fa-cloud-arrow-down mr-1"></i>cloud</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-gamepad')"><i class="fa-solid fa-gamepad mr-1"></i>game</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-mobile-alt')"><i class="fa-solid fa-mobile-alt mr-1"></i>mobile</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-link')"><i class="fa-solid fa-link mr-1"></i>link</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-regular fa-heart')"><i class="fa-regular fa-heart mr-1"></i>tim</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-star')"><i class="fa-solid fa-star mr-1"></i>sao</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-fire')"><i class="fa-solid fa-fire mr-1"></i>hot</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-bolt')"><i class="fa-solid fa-bolt mr-1"></i>nhanh</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-crown')"><i class="fa-solid fa-crown mr-1"></i>crown</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-gift')"><i class="fa-solid fa-gift mr-1"></i>quà</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-rocket')"><i class="fa-solid fa-rocket mr-1"></i>rocket</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-shield-alt')"><i class="fa-solid fa-shield-alt mr-1"></i>bảo mật</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-telegram')"><i class="fa-brands fa-telegram mr-1"></i>telegram</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-facebook')"><i class="fa-brands fa-facebook mr-1"></i>facebook</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-tiktok')"><i class="fa-brands fa-tiktok mr-1"></i>tiktok</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-discord')"><i class="fa-brands fa-discord mr-1"></i>discord</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-youtube')"><i class="fa-brands fa-youtube mr-1"></i>youtube</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-instagram')"><i class="fa-brands fa-instagram mr-1"></i>instagram</span>
                  </div>
                </div>
                <div class="text-xs text-gray-500 mt-2 pt-2 border-t border-gray-200 flex items-center gap-2">
                  <i class="fa-solid fa-eye mr-1"></i> Preview:
                  <i class="<?php echo safeHtml($card['icon'] ?? 'fa-regular fa-thumbs-up'); ?> text-blue-500"></i>
                  <span class="font-medium"><?php echo safeHtml($card['title'] ?? 'Card'); ?></span>
                  <span class="text-gray-400 text-xs">(<?php echo safeHtml($card['subtitle'] ?? ''); ?>)</span>
                </div>
              </div>
              <?php
                    endforeach;
                else:
                ?>
              <div class="navbar-card bg-gray-50 rounded-xl p-4 border border-gray-200">
                <div class="flex justify-between items-start mb-3">
                  <div class="flex items-center gap-2">
                    <span class="bg-orange-100 text-orange-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                    <span class="text-xs text-gray-400">Navbar Card</span>
                  </div>
                  <button type="button" onclick="removeNavbarCard(this)" class="text-red-500 hover:text-red-700">
                    <i class="fa-solid fa-trash-alt"></i>
                  </button>
                </div>
                <div class="mb-3">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-heading mr-1"></i> Tiêu đề Card
                  </label>
                  <input type="text" name="navbar_title_0" placeholder="VD: thanhtapcode" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
                </div>
                <div class="mb-3">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-tag mr-1"></i> Phụ đề
                  </label>
                  <input type="text" name="navbar_subtitle_0" placeholder="VD: t.me" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
                </div>
                <div class="mb-3">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-link mr-1"></i> Đường dẫn URL
                  </label>
                  <input type="text" name="navbar_url_0" placeholder="https://t.me/..." class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
                </div>
                <div class="mb-2">
                  <label class="block text-xs text-gray-600 mb-1">
                    <i class="fa-solid fa-icons mr-1"></i> Icon
                  </label>
                  <div class="flex gap-2">
                    <input type="text" name="navbar_icon_0" value="fa-regular fa-thumbs-up" placeholder="fa-regular fa-thumbs-up" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 font-mono focus:border-blue-500">
                    <button type="button" onclick="openNavbarIconPicker(this)" class="bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">
                      <i class="fa-solid fa-icons"></i>
                    </button>
                  </div>
                  <div class="mt-2 flex gap-2 flex-wrap">
                    <span class="text-xs text-gray-500">Gợi ý:</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-regular fa-thumbs-up')"><i class="fa-regular fa-thumbs-up mr-1"></i>thích</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-qrcode')"><i class="fa-solid fa-qrcode mr-1"></i>qr</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-download')"><i class="fa-solid fa-download mr-1"></i>tải</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-telegram')"><i class="fa-brands fa-telegram mr-1"></i>telegram</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-facebook')"><i class="fa-brands fa-facebook mr-1"></i>facebook</span>
                    <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-tiktok')"><i class="fa-brands fa-tiktok mr-1"></i>tiktok</span>
                  </div>
                </div>
                <div class="text-xs text-gray-500 mt-2 pt-2 border-t border-gray-200 flex items-center gap-2">
                  <i class="fa-solid fa-eye mr-1"></i> Preview:
                  <i class="fa-regular fa-thumbs-up text-blue-500"></i>
                  <span class="font-medium">Card mới</span>
                </div>
              </div>
              <?php endif; ?>
            </div>

            <div class="pt-4 border-t border-gray-200 mt-4">
              <button type="submit" name="update_navbar_cards" class="btn-primary px-6 py-2.5 text-white rounded-lg text-sm font-medium">
                <i class="fa-solid fa-save mr-2"></i> Lưu Navbar Cards
              </button>
            </div>
          </form>
        </div>
      </div>

      <script>
        // Particles effect
        const particlesContainer = document.getElementById('particles');

        function createParticle() {
          if (!particlesContainer) return;
          const p = document.createElement('div');
          p.classList.add('particle');
          const size = Math.random() * 3 + 2;
          p.style.width = size + 'px';
          p.style.height = size + 'px';
          p.style.left = Math.random() * window.innerWidth + 'px';
          p.style.animationDuration = (Math.random() * 5 + 5) + 's';
          particlesContainer.appendChild(p);
          setTimeout(() => p.remove(), 10000);
        }
        setInterval(createParticle, 200);

        // Sidebar functions
        function openMobileSidebar() {
          document.getElementById('mobileSidebar').classList.add('open');
          document.getElementById('overlay').classList.add('show');
          document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
          document.getElementById('mobileSidebar').classList.remove('open');
          document.getElementById('overlay').classList.remove('show');
          document.body.style.overflow = '';
        }

        // Scroll to section
        function scrollToSection(sectionId) {
          const element = document.getElementById(sectionId);
          if (element) {
            const offset = 80;
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;
            window.scrollTo({
              top: offsetPosition,
              behavior: 'smooth'
            });
          }
          closeMobileSidebar();
        }

        // Image preview
        function previewImage(inputId, previewId) {
          const input = document.getElementById(inputId);
          const preview = document.getElementById(previewId);
          if (input && preview && input.value) {
            preview.innerHTML = '<img src="' + input.value + '" alt="Preview" onerror="this.src=\'https://placehold.co/400x200?text=Invalid+Image\'" style="max-height: 150px; object-fit: cover;">';
            preview.classList.remove('hidden');
          } else if (preview) {
            preview.classList.add('hidden');
          }
        }

        // Social rows - add and remove
        function addSocialRow() {
          const container = document.getElementById('social-links-container');
          const newRow = document.createElement('div');
          newRow.className = 'social-link-row flex flex-col sm:flex-row gap-2 p-3 bg-gray-50 rounded-lg';
          newRow.innerHTML = `
                <input type="text" name="social_name[]" placeholder="Tên" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200">
                <input type="url" name="social_url[]" placeholder="URL" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200">
                <input type="text" name="social_icon[]" placeholder="Icon class" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200">
                <button type="button" onclick="removeSocialRow(this)" class="text-red-500 px-2 hover:text-red-700">
                    <i class="fa-solid fa-trash-alt"></i>
                </button>
            `;
          container.appendChild(newRow);
        }

        function removeSocialRow(btn) {
          const row = btn.closest('.social-link-row');
          const rows = document.querySelectorAll('.social-link-row');
          if (rows.length > 1) {
            row.remove();
          } else {
            row.querySelectorAll('input').forEach(input => input.value = '');
          }
        }

        // Sidebar menu rows
        function addSidebarRow() {
          const container = document.getElementById('sidebar-menu-container');
          const newRow = document.createElement('div');
          newRow.className = 'sidebar-menu-row flex flex-col sm:flex-row gap-2 p-3 bg-gray-50 rounded-lg';
          newRow.innerHTML = `
                <input type="text" name="menu_title[]" placeholder="Tiêu đề menu" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200">
                <input type="text" name="menu_icon[]" placeholder="Icon class" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200">
                <input type="text" name="menu_url[]" placeholder="URL" class="flex-2 px-3 py-2 rounded-lg text-sm border border-gray-200">
                <button type="button" onclick="removeSidebarRow(this)" class="text-red-500 px-2 hover:text-red-700">
                    <i class="fa-solid fa-trash-alt"></i>
                </button>
            `;
          container.appendChild(newRow);
        }

        function removeSidebarRow(btn) {
          const row = btn.closest('.sidebar-menu-row');
          const rows = document.querySelectorAll('.sidebar-menu-row');
          if (rows.length > 1) {
            row.remove();
          } else {
            row.querySelectorAll('input').forEach(input => input.value = '');
          }
        }

        // Active menu highlight on scroll
        const sections = ['settings', 'profile', 'categories', 'social', 'music', 'navbar', 'sidebar_menu_section', 'background_section'];

        window.addEventListener('scroll', function() {
          let current = '';
          for (const section of sections) {
            const element = document.getElementById(section);
            if (element) {
              const rect = element.getBoundingClientRect();
              if (rect.top <= 100 && rect.bottom >= 100) {
                current = section;
                break;
              }
            }
          }

          document.querySelectorAll('.menu-item').forEach(item => {
            item.classList.remove('active');
            if (item.textContent.toLowerCase().includes(current.replace('_section', ''))) {
              item.classList.add('active');
            }
          });
        });

        // Trigger preview on load
        document.addEventListener('DOMContentLoaded', function() {
          previewImage('bg_url', 'bg_preview');
          previewImage('bg_mobile_url', 'bg_mobile_preview');
          previewImage('profile_avatar', 'avatar_preview');
        });

        // Close mobile sidebar when clicking outside
        document.addEventListener('click', function(event) {
          const sidebar = document.getElementById('mobileSidebar');
          const menuBtn = event.target.closest('.mobile-menu-btn');
          if (sidebar && sidebar.classList.contains('open') && !sidebar.contains(event.target) && !menuBtn) {
            closeMobileSidebar();
          }
        });

        // ==================== SIDEBAR MENU FUNCTIONS ====================

        // Thêm mới một row menu
        function addSidebarRow() {
          const container = document.getElementById('sidebar-menu-container');
          const newRow = document.createElement('div');
          newRow.className = 'sidebar-menu-row bg-gray-50 rounded-lg p-4 border border-gray-200';
          newRow.innerHTML = `
        <div class="mb-2">
            <label class="block text-xs text-gray-600 mb-1">Tiêu đề menu</label>
            <input type="text" name="menu_title[]" placeholder="VD: Trang chủ" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300" oninput="updateMenuPreview(this)">
        </div>
        <div class="mb-2">
            <label class="block text-xs text-gray-600 mb-1">Icon class</label>
            <div class="flex gap-2">
                <input type="text" name="menu_icon[]" placeholder="VD: fa-solid fa-home" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-300" oninput="updateMenuPreview(this)">
                <button type="button" onclick="openMenuIconPicker(this)" class="bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">
                    <i class="fa-solid fa-icons"></i>
                </button>
            </div>
            <div class="mt-2 flex gap-2 flex-wrap">
                <span class="text-xs text-gray-500">Gợi ý:</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-home')"><i class="fa-solid fa-home mr-1"></i>home</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-user-shield')"><i class="fa-solid fa-user-shield mr-1"></i>admin</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-gear')"><i class="fa-solid fa-gear mr-1"></i>cài đặt</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-question-circle')"><i class="fa-solid fa-question-circle mr-1"></i>hỗ trợ</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-brands fa-telegram')"><i class="fa-brands fa-telegram mr-1"></i>telegram</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-envelope')"><i class="fa-solid fa-envelope mr-1"></i>liên hệ</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-folder')"><i class="fa-solid fa-folder mr-1"></i>danh mục</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setMenuIcon(this, 'fa-solid fa-music')"><i class="fa-solid fa-music mr-1"></i>nhạc</span>
            </div>
        </div>
        <div class="mb-2">
            <label class="block text-xs text-gray-600 mb-1">Đường dẫn URL</label>
            <input type="text" name="menu_url[]" placeholder="VD: index.php" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300" oninput="updateMenuPreview(this)">
        </div>
        <div class="text-xs text-gray-500 mt-2 pt-2 border-t border-gray-200">
            <i class="fa-solid fa-eye mr-1"></i> Preview: 
            <i class="fa-solid fa-link mr-1 preview-icon"></i>
            <span class="preview-title">Menu mới</span>
            <span class="text-gray-400"> → </span>
            <span class="preview-url text-blue-500">#</span>
        </div>
        <button type="button" onclick="removeSidebarRow(this)" class="text-red-500 text-sm mt-3 w-full text-center py-1 border-t border-gray-200 pt-2">
            <i class="fa-solid fa-trash-alt mr-1"></i> Xóa menu
        </button>
    `;
          container.appendChild(newRow);
        }

        // Xóa một row menu
        function removeSidebarRow(btn) {
          const row = btn.closest('.sidebar-menu-row');
          const rows = document.querySelectorAll('.sidebar-menu-row');
          if (rows.length > 1) {
            row.remove();
          } else {
            // Nếu chỉ còn 1 row, clear các input
            row.querySelectorAll('input').forEach(input => {
              input.value = '';
            });
            // Reset preview
            const previewIcon = row.querySelector('.preview-icon');
            const previewTitle = row.querySelector('.preview-title');
            const previewUrl = row.querySelector('.preview-url');
            if (previewIcon) previewIcon.className = 'fa-solid fa-link mr-1 preview-icon';
            if (previewTitle) previewTitle.textContent = 'Menu mới';
            if (previewUrl) previewUrl.textContent = '#';
          }
        }

        // Cập nhật preview khi người dùng nhập
        function updateMenuPreview(input) {
          const row = input.closest('.sidebar-menu-row');
          if (!row) return;

          const titleInput = row.querySelector('input[name="menu_title[]"]');
          const iconInput = row.querySelector('input[name="menu_icon[]"]');
          const urlInput = row.querySelector('input[name="menu_url[]"]');
          const previewIcon = row.querySelector('.preview-icon');
          const previewTitle = row.querySelector('.preview-title');
          const previewUrl = row.querySelector('.preview-url');

          if (titleInput && previewTitle) {
            previewTitle.textContent = titleInput.value || 'Menu mới';
          }
          if (iconInput && previewIcon) {
            const iconClass = iconInput.value || 'fa-solid fa-link';
            previewIcon.className = iconClass + ' mr-1 preview-icon';
          }
          if (urlInput && previewUrl) {
            previewUrl.textContent = urlInput.value || '#';
          }
        }

        // Set giá trị icon từ gợi ý
        function setMenuIcon(element, iconClass) {
          const row = element.closest('.sidebar-menu-row');
          if (row) {
            const iconInput = row.querySelector('input[name="menu_icon[]"]');
            if (iconInput) {
              iconInput.value = iconClass;
              // Cập nhật preview
              const previewIcon = row.querySelector('.preview-icon');
              if (previewIcon) {
                previewIcon.className = iconClass + ' mr-1 preview-icon';
              }
            }
          }
        }

        // Open icon picker
        function openMenuIconPicker(btn) {
          const row = btn.closest('.sidebar-menu-row');
          const iconInput = row.querySelector('input[name="menu_icon[]"]');
          if (iconInput) {
            alert('Bạn có thể nhập trực tiếp icon class hoặc click vào các gợi ý bên dưới.\n\nCác icon class phổ biến:\n- fa-solid fa-home (Trang chủ)\n- fa-solid fa-user-shield (Admin)\n- fa-solid fa-gear (Cài đặt)\n- fa-solid fa-question-circle (Hỗ trợ)\n- fa-brands fa-telegram (Telegram)');
          }
        }

        // Gắn sự kiện preview cho các row có sẵn khi load trang
        document.addEventListener('DOMContentLoaded', function() {
          document.querySelectorAll('.sidebar-menu-row').forEach(row => {
            const inputs = row.querySelectorAll('input');
            inputs.forEach(input => {
              input.addEventListener('input', function() {
                updateMenuPreview(this);
              });
            });
          });
        });

        // ==================== SOCIAL LINKS FUNCTIONS ====================

        // Thêm mới một social link row
        function addSocialRow() {
          const container = document.getElementById('social-links-container');
          const newRow = document.createElement('div');
          newRow.className = 'social-link-row bg-gray-50 rounded-lg p-4 border border-gray-200';
          newRow.innerHTML = `
        <div class="mb-2">
            <label class="block text-xs text-gray-600 mb-1">Tên mạng xã hội</label>
            <input type="text" name="social_name[]" placeholder="VD: Facebook" class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
        </div>
        <div class="mb-2">
            <label class="block text-xs text-gray-600 mb-1">URL liên kết</label>
            <input type="url" name="social_url[]" placeholder="https://..." class="w-full px-3 py-2 rounded-lg text-sm border border-gray-300">
        </div>
        <div>
            <label class="block text-xs text-gray-600 mb-1">Icon class</label>
            <div class="flex gap-2">
                <input type="text" name="social_icon[]" placeholder="fab fa-facebook" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-300">
                <button type="button" onclick="openIconPicker(this)" class="bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">
                    <i class="fa-solid fa-icons"></i>
                </button>
            </div>
            <div class="mt-2 flex gap-2 flex-wrap">
                <span class="text-xs text-gray-500">Gợi ý:</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-facebook')"><i class="fab fa-facebook mr-1"></i>fb</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-telegram')"><i class="fab fa-telegram mr-1"></i>tele</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-tiktok')"><i class="fab fa-tiktok mr-1"></i>tt</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-discord')"><i class="fab fa-discord mr-1"></i>dc</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-instagram')"><i class="fab fa-instagram mr-1"></i>ig</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-youtube')"><i class="fab fa-youtube mr-1"></i>yt</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-twitter')"><i class="fab fa-twitter mr-1"></i>x</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer" onclick="setSocialIcon(this, 'fab fa-github')"><i class="fab fa-github mr-1"></i>git</span>
            </div>
        </div>
        <button type="button" onclick="removeSocialRow(this)" class="text-red-500 text-sm mt-3 w-full text-center py-1 border-t border-gray-200 pt-2">
            <i class="fa-solid fa-trash-alt mr-1"></i> Xóa
        </button>
    `;
          container.appendChild(newRow);
        }

        // Xóa một social link row
        function removeSocialRow(btn) {
          const row = btn.closest('.social-link-row');
          const rows = document.querySelectorAll('.social-link-row');
          if (rows.length > 1) {
            row.remove();
          } else {
            // Nếu chỉ còn 1 row, clear các input
            row.querySelectorAll('input').forEach(input => {
              input.value = '';
            });
          }
        }

        // Set icon value từ gợi ý
        function setSocialIcon(element, iconClass) {
          const row = element.closest('.social-link-row');
          if (row) {
            const iconInput = row.querySelector('input[name="social_icon[]"]');
            if (iconInput) {
              iconInput.value = iconClass;
            }
          }
        }

        // Open icon picker (có thể mở rộng sau)
        function openIconPicker(btn) {
          const row = btn.closest('.social-link-row');
          const iconInput = row.querySelector('input[name="social_icon[]"]');
          if (iconInput) {
            // Tạm thời alert để người dùng biết có thể nhập tay
            alert('Bạn có thể nhập trực tiếp icon class hoặc click vào các gợi ý bên dưới.\n\nVí dụ:\n- fab fa-facebook\n- fab fa-telegram\n- fab fa-tiktok\n- fab fa-discord');
          }
        }

        // ==================== MUSIC CARD FUNCTIONS ====================

        // Thêm mới một thẻ bài hát
        function addMusicCard() {
          const container = document.getElementById('music-list-container');
          const cardCount = container.querySelectorAll('.music-card').length;
          const newCard = document.createElement('div');
          newCard.className = 'music-card bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition';
          newCard.innerHTML = `
        <div class="flex justify-between items-start mb-3">
            <div class="flex items-center gap-2">
                <span class="bg-green-100 text-green-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">${cardCount + 1}</span>
                <span class="text-xs text-gray-400">Bài hát</span>
            </div>
            <button type="button" onclick="removeMusicCard(this)" class="text-red-500 hover:text-red-700">
                <i class="fa-solid fa-trash-alt"></i>
            </button>
        </div>
        <div class="mb-3">
            <label class="block text-xs text-gray-600 mb-1">
                <i class="fa-solid fa-heading mr-1"></i> Tên bài hát
            </label>
            <input type="text" name="song_names[]" placeholder="VD: Cảm Ơn Em Remix" 
                   class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-200">
        </div>
        <div class="mb-2">
            <label class="block text-xs text-gray-600 mb-1">
                <i class="fa-solid fa-link mr-1"></i> Đường dẫn file
            </label>
            <div class="flex gap-2">
                <input type="text" name="playlist[]" placeholder="/music/tenbaihat.mp3" 
                       class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-200">
                <button type="button" onclick="playMusicPreview(this)" class="bg-green-100 text-green-600 px-3 py-2 rounded-lg text-sm hover:bg-green-200 transition">
                    <i class="fa-solid fa-play"></i>
                </button>
            </div>
            <p class="text-xs text-gray-400 mt-1">
                <i class="fa-regular fa-folder-open mr-1"></i> Đặt file .mp3 trong thư mục /music/
            </p>
        </div>
    `;
          container.appendChild(newCard);
          updateMusicCardNumbers();
        }

        // Xóa một thẻ bài hát
        function removeMusicCard(btn) {
          const card = btn.closest('.music-card');
          const cards = document.querySelectorAll('.music-card');
          if (cards.length > 1) {
            card.remove();
            updateMusicCardNumbers();
          } else {
            // Nếu chỉ còn 1 card, clear các input
            card.querySelectorAll('input').forEach(input => {
              input.value = '';
            });
            // Reset số thứ tự
            const numberSpan = card.querySelector('.bg-green-100');
            if (numberSpan) numberSpan.textContent = '1';
          }
        }

        // Cập nhật số thứ tự các thẻ
        function updateMusicCardNumbers() {
          const cards = document.querySelectorAll('.music-card');
          cards.forEach((card, index) => {
            const numberSpan = card.querySelector('.bg-green-100');
            if (numberSpan) {
              numberSpan.textContent = (index + 1).toString();
            }
          });
        }

        // Preview nhạc
        let currentAudioCard = null;
        let currentPlayButton = null;

        function playMusicPreview(btn) {
          const card = btn.closest('.music-card');
          const urlInput = card.querySelector('input[name="playlist[]"]');
          const audio = document.getElementById('musicPreview');

          // Nếu đang phát cùng nút này thì dừng
          if (currentPlayButton === btn && currentAudioCard && !currentAudioCard.paused) {
            currentAudioCard.pause();
            currentAudioCard.currentTime = 0;
            btn.innerHTML = '<i class="fa-solid fa-play"></i>';
            btn.classList.remove('bg-red-100', 'text-red-600', 'hover:bg-red-200');
            btn.classList.add('bg-green-100', 'text-green-600', 'hover:bg-green-200');
            currentPlayButton = null;
            return;
          }

          // Dừng bài đang phát trước đó
          if (currentAudioCard) {
            currentAudioCard.pause();
            currentAudioCard.currentTime = 0;
            if (currentPlayButton) {
              currentPlayButton.innerHTML = '<i class="fa-solid fa-play"></i>';
              currentPlayButton.classList.remove('bg-red-100', 'text-red-600', 'hover:bg-red-200');
              currentPlayButton.classList.add('bg-green-100', 'text-green-600', 'hover:bg-green-200');
            }
          }

          if (urlInput && urlInput.value) {
            const musicUrl = urlInput.value;

            // Kiểm tra định dạng file
            if (!musicUrl.endsWith('.mp3') && !musicUrl.endsWith('.wav') && !musicUrl.endsWith('.ogg')) {
              alert('⚠️ Định dạng file không được hỗ trợ!\nHỗ trợ: .mp3, .wav, .ogg');
              return;
            }

            audio.src = musicUrl;
            audio.play().then(() => {
              currentAudioCard = audio;
              currentPlayButton = btn;
              btn.innerHTML = '<i class="fa-solid fa-stop"></i>';
              btn.classList.remove('bg-green-100', 'text-green-600', 'hover:bg-green-200');
              btn.classList.add('bg-red-100', 'text-red-600', 'hover:bg-red-200');

              // Đổi lại nút khi hết nhạc
              audio.onended = function() {
                btn.innerHTML = '<i class="fa-solid fa-play"></i>';
                btn.classList.remove('bg-red-100', 'text-red-600', 'hover:bg-red-200');
                btn.classList.add('bg-green-100', 'text-green-600', 'hover:bg-green-200');
                currentPlayButton = null;
                currentAudioCard = null;
              };

              // Xử lý lỗi phát nhạc
              audio.onerror = function() {
                btn.innerHTML = '<i class="fa-solid fa-play"></i>';
                btn.classList.remove('bg-red-100', 'text-red-600', 'hover:bg-red-200');
                btn.classList.add('bg-green-100', 'text-green-600', 'hover:bg-green-200');
                currentPlayButton = null;
                currentAudioCard = null;
                alert('❌ Không thể phát file nhạc!\nKiểm tra lại đường dẫn: ' + musicUrl);
              };
            }).catch(err => {
              alert('❌ Không thể phát file nhạc!\nLỗi: ' + err.message + '\n\nĐường dẫn: ' + musicUrl);
            });
          } else {
            alert('⚠️ Vui lòng nhập đường dẫn file nhạc trước!');
          }
        }

        // Tự động cập nhật số thứ tự khi load trang
        document.addEventListener('DOMContentLoaded', function() {
          updateMusicCardNumbers();
        });

        // ==================== NAVBAR CARDS FUNCTIONS ====================

        // Thêm mới một Navbar Card
        let navbarCardCount = document.querySelectorAll('.navbar-card').length;

        function addNavbarCard() {
          const container = document.getElementById('navbar-cards-container');
          const cardCount = container.querySelectorAll('.navbar-card').length;
          const newIndex = cardCount;

          const newCard = document.createElement('div');
          newCard.className = 'navbar-card bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition';
          newCard.innerHTML = `
        <div class="flex justify-between items-start mb-3">
            <div class="flex items-center gap-2">
                <span class="bg-orange-100 text-orange-600 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold">${cardCount + 1}</span>
                <span class="text-xs text-gray-400">Navbar Card</span>
            </div>
            <button type="button" onclick="removeNavbarCard(this)" class="text-red-500 hover:text-red-700">
                <i class="fa-solid fa-trash-alt"></i>
            </button>
        </div>
        <div class="mb-3">
            <label class="block text-xs text-gray-600 mb-1">
                <i class="fa-solid fa-heading mr-1"></i> Tiêu đề Card
            </label>
            <input type="text" name="navbar_title_${newIndex}" placeholder="VD: thanhtapcode" 
                   class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
        </div>
        <div class="mb-3">
            <label class="block text-xs text-gray-600 mb-1">
                <i class="fa-solid fa-tag mr-1"></i> Phụ đề
            </label>
            <input type="text" name="navbar_subtitle_${newIndex}" placeholder="VD: t.me" 
                   class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
        </div>
        <div class="mb-3">
            <label class="block text-xs text-gray-600 mb-1">
                <i class="fa-solid fa-link mr-1"></i> Đường dẫn URL
            </label>
            <input type="text" name="navbar_url_${newIndex}" placeholder="https://t.me/..." 
                   class="w-full px-3 py-2 rounded-lg text-sm border border-gray-200 focus:border-blue-500">
        </div>
        <div class="mb-2">
            <label class="block text-xs text-gray-600 mb-1">
                <i class="fa-solid fa-icons mr-1"></i> Icon
            </label>
            <div class="flex gap-2">
                <input type="text" name="navbar_icon_${newIndex}" value="fa-regular fa-thumbs-up" 
                       placeholder="fa-regular fa-thumbs-up" class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200 font-mono focus:border-blue-500">
                <button type="button" onclick="openNavbarIconPicker(this)" class="bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">
                    <i class="fa-solid fa-icons"></i>
                </button>
            </div>
            <div class="mt-2 flex gap-2 flex-wrap">
                <span class="text-xs text-gray-500">Gợi ý:</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-regular fa-thumbs-up')"><i class="fa-regular fa-thumbs-up mr-1"></i>thích</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-qrcode')"><i class="fa-solid fa-qrcode mr-1"></i>qr</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-solid fa-download')"><i class="fa-solid fa-download mr-1"></i>tải</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-telegram')"><i class="fa-brands fa-telegram mr-1"></i>telegram</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-facebook')"><i class="fa-brands fa-facebook mr-1"></i>facebook</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-tiktok')"><i class="fa-brands fa-tiktok mr-1"></i>tiktok</span>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded cursor-pointer hover:bg-gray-300" onclick="setNavbarIcon(this, 'fa-brands fa-discord')"><i class="fa-brands fa-discord mr-1"></i>discord</span>
            </div>
        </div>
        <div class="text-xs text-gray-500 mt-2 pt-2 border-t border-gray-200 flex items-center gap-2">
            <i class="fa-solid fa-eye mr-1"></i> Preview: 
            <i class="fa-regular fa-thumbs-up text-blue-500"></i>
            <span class="font-medium">Card mới</span>
        </div>
    `;
          container.appendChild(newCard);
          updateNavbarCardNumbers();
        }

        // Xóa một Navbar Card
        function removeNavbarCard(btn) {
          const card = btn.closest('.navbar-card');
          const cards = document.querySelectorAll('.navbar-card');
          if (cards.length > 1) {
            card.remove();
            updateNavbarCardNumbers();
          } else {
            // Nếu chỉ còn 1 card, clear các input
            card.querySelectorAll('input').forEach(input => {
              input.value = '';
            });
            // Reset icon
            const iconInput = card.querySelector('input[name^="navbar_icon_"]');
            if (iconInput) iconInput.value = 'fa-regular fa-thumbs-up';
            // Reset preview
            const previewIcon = card.querySelector('.text-gray-500 i');
            if (previewIcon) previewIcon.className = 'fa-regular fa-thumbs-up text-blue-500';
            const previewTitle = card.querySelector('.text-gray-500 .font-medium');
            if (previewTitle) previewTitle.textContent = 'Card mới';
            // Reset số thứ tự
            const numberSpan = card.querySelector('.bg-orange-100');
            if (numberSpan) numberSpan.textContent = '1';
          }
        }

        // Cập nhật số thứ tự các card
        function updateNavbarCardNumbers() {
          const cards = document.querySelectorAll('.navbar-card');
          cards.forEach((card, index) => {
            const numberSpan = card.querySelector('.bg-orange-100');
            if (numberSpan) {
              numberSpan.textContent = (index + 1).toString();
            }
            // Cập nhật name attributes
            const titleInput = card.querySelector('input[name^="navbar_title_"]');
            const subtitleInput = card.querySelector('input[name^="navbar_subtitle_"]');
            const urlInput = card.querySelector('input[name^="navbar_url_"]');
            const iconInput = card.querySelector('input[name^="navbar_icon_"]');

            if (titleInput) titleInput.name = `navbar_title_${index}`;
            if (subtitleInput) subtitleInput.name = `navbar_subtitle_${index}`;
            if (urlInput) urlInput.name = `navbar_url_${index}`;
            if (iconInput) iconInput.name = `navbar_icon_${index}`;
          });
        }

        // Set icon cho Navbar Card từ gợi ý
        function setNavbarIcon(element, iconClass) {
          const card = element.closest('.navbar-card');
          if (card) {
            const iconInput = card.querySelector('input[name^="navbar_icon_"]');
            if (iconInput) {
              iconInput.value = iconClass;
              // Cập nhật preview icon
              const previewIcon = card.querySelector('.text-gray-500 i');
              if (previewIcon) {
                previewIcon.className = iconClass + ' text-blue-500';
              }
            }
          }
        }

        // Open icon picker
        function openNavbarIconPicker(btn) {
          const card = btn.closest('.navbar-card');
          const iconInput = card.querySelector('input[name^="navbar_icon_"]');
          if (iconInput) {
            alert('🎨 Chọn icon:\n\nBạn có thể nhập trực tiếp class icon hoặc click vào các gợi ý bên dưới.\n\nCác icon phổ biến:\n👍 fa-regular fa-thumbs-up\n📱 fa-solid fa-qrcode\n⬇️ fa-solid fa-download\n📨 fa-brands fa-telegram\n📘 fa-brands fa-facebook\n🎵 fa-brands fa-tiktok\n🎙️ fa-brands fa-discord');
          }
        }

        // Cập nhật preview khi người dùng nhập
        function updateNavbarPreview(input) {
          const card = input.closest('.navbar-card');
          if (!card) return;

          const titleInput = card.querySelector('input[name^="navbar_title_"]');
          const iconInput = card.querySelector('input[name^="navbar_icon_"]');
          const previewIcon = card.querySelector('.text-gray-500 i');
          const previewTitle = card.querySelector('.text-gray-500 .font-medium');

          if (titleInput && previewTitle) {
            previewTitle.textContent = titleInput.value || 'Card mới';
          }
          if (iconInput && previewIcon) {
            const iconClass = iconInput.value || 'fa-regular fa-thumbs-up';
            previewIcon.className = iconClass + ' text-blue-500';
          }
        }

        // Gắn sự kiện cho các input
        document.addEventListener('DOMContentLoaded', function() {
          document.querySelectorAll('.navbar-card input').forEach(input => {
            input.addEventListener('input', function() {
              updateNavbarPreview(this);
            });
          });
          updateNavbarCardNumbers();
        });

        // ==================== CATEGORIES FUNCTIONS ====================

        // Hiển thị modal thêm mục vào danh mục
        function showAddItemToCategory(categoryName) {
          const modal = document.getElementById('addItemModal');
          const categoryNameSpan = document.getElementById('modalCategoryName');
          const categoryInput = document.getElementById('modalCategoryInput');

          categoryNameSpan.textContent = categoryName;
          categoryInput.value = categoryName;

          // Reset form
          const form = document.getElementById('addItemForm');
          form.reset();

          // Reset preview
          const previewDiv = document.getElementById('modal_image_preview');
          if (previewDiv) previewDiv.classList.add('hidden');

          modal.classList.remove('hidden');
          modal.classList.add('flex');
        }

        // Preview ảnh trong modal
        function previewModalImage() {
          const input = document.getElementById('modal_item_image');
          const previewDiv = document.getElementById('modal_image_preview');
          const previewImg = document.getElementById('modal_preview_img');

          if (input && input.value) {
            previewImg.src = input.value;
            previewDiv.classList.remove('hidden');
            previewImg.onerror = function() {
              this.src = 'https://placehold.co/56x56?text=Invalid';
            };
          } else {
            previewDiv.classList.add('hidden');
          }
        }

        // Đóng modal
        function closeModal(event) {
          const modal = document.getElementById('addItemModal');
          if (event && event.target !== modal && event.target !== document.getElementById('addItemModal')) {
            return;
          }
          modal.classList.add('hidden');
          modal.classList.remove('flex');
        }

        // Xóa toàn bộ danh mục
        function deleteCategory(categoryName) {
          if (confirm(`Bạn có chắc chắn muốn xóa toàn bộ danh mục "${categoryName}" và tất cả mục bên trong?`)) {
            // Tạo form gửi request xóa
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_full_category';
            input.value = categoryName;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
          }
        }

        // Preview ảnh cho form thêm mới
        function previewImage(inputId, previewId) {
          const input = document.getElementById(inputId);
          const preview = document.getElementById(previewId);
          const previewImg = document.getElementById('item_preview_img');

          if (input && preview && input.value) {
            previewImg.src = input.value;
            preview.classList.remove('hidden');
            previewImg.onerror = function() {
              this.src = 'https://placehold.co/56x56?text=Invalid';
            };
          } else if (preview) {
            preview.classList.add('hidden');
          }
        }

        // Cập nhật số lượng mục trong danh mục (nếu cần)
        function updateCategoryCount() {
          // Có thể thêm chức năng cập nhật số lượng sau
        }

        // ==================== PROFILE FUNCTIONS ====================

        // Live preview khi nhập liệu
        document.addEventListener('DOMContentLoaded', function() {
          // Lắng nghe sự kiện trên các input
          const nameInput = document.querySelector('input[name="profile_name"]');
          const titleInput = document.querySelector('input[name="profile_title"]');
          const subtitleInput = document.querySelector('input[name="profile_subtitle"]');

          if (nameInput) {
            nameInput.addEventListener('input', function() {
              document.getElementById('liveNamePreview').textContent = this.value || '@thanhtapcode';
            });
          }
          if (titleInput) {
            titleInput.addEventListener('input', function() {
              document.getElementById('liveTitlePreview').textContent = this.value || 'Chưa có tiêu đề';
            });
          }
          if (subtitleInput) {
            subtitleInput.addEventListener('input', function() {
              document.getElementById('liveSubtitlePreview').textContent = this.value || 'Chưa có phụ đề';
            });
          }
        });

        // Preview avatar khi chọn file (upload lên hosting)
        function previewAvatar(input) {
          const modal = document.getElementById('uploadAvatarModal');
          const file = input.files[0];

          if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
              document.getElementById('uploadPreview').src = e.target.result;
              modal.classList.remove('hidden');
              modal.classList.add('flex');
            };
            reader.readAsDataURL(file);
          }
        }

        // Preview ảnh trong modal
        function previewUploadAvatar(input) {
          const file = input.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
              document.getElementById('uploadPreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
          }
        }

        // Upload avatar (cần có API upload hoặc lưu base64)
        function uploadAvatar() {
          const fileInput = document.getElementById('avatarFileInput');
          const file = fileInput.files[0];

          if (file) {
            // Cách 1: Lưu dưới dạng base64 (phù hợp với JSON)
            const reader = new FileReader();
            reader.onload = function(e) {
              const base64 = e.target.result;
              document.getElementById('profile_avatar_input').value = base64;
              document.getElementById('avatarPreview').innerHTML = '<img src="' + base64 + '" class="w-full h-full object-cover">';
              document.getElementById('liveAvatarPreview').src = base64;
              closeAvatarModal();

              // Hiển thị thông báo
              showToast('Avatar đã được cập nhật! Nhấn "Lưu hồ sơ" để lưu lại.', 'success');
            };
            reader.readAsDataURL(file);
          }
        }

        // Đóng modal avatar
        function closeAvatarModal(event) {
          const modal = document.getElementById('uploadAvatarModal');
          if (event && event.target !== modal) return;
          modal.classList.add('hidden');
          modal.classList.remove('flex');

          // Reset file input
          document.getElementById('avatarFileInput').value = '';
          document.getElementById('avatarUpload').value = '';
        }

        // Toast thông báo
        function showToast(message, type = 'success') {
          // Kiểm tra toast đã tồn tại chưa
          let toast = document.getElementById('customToast');
          if (!toast) {
            toast = document.createElement('div');
            toast.id = 'customToast';
            toast.className = 'fixed bottom-20 left-1/2 transform -translate-x-1/2 z-50 transition-all duration-300 opacity-0 translate-y-10';
            toast.innerHTML = `
            <div class="bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 text-sm">
                <i class="fa-solid fa-check-circle text-green-400"></i>
                <span id="toastMessage"></span>
            </div>
        `;
            document.body.appendChild(toast);
          }

          const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
          const iconColor = type === 'success' ? 'text-green-400' : 'text-red-400';
          toast.querySelector('i').className = `fa-solid ${icon} ${iconColor}`;
          toast.querySelector('#toastMessage').textContent = message;

          toast.classList.remove('opacity-0', 'translate-y-10');
          toast.classList.add('opacity-100', 'translate-y-0');

          setTimeout(() => {
            toast.classList.remove('opacity-100', 'translate-y-0');
            toast.classList.add('opacity-0', 'translate-y-10');
          }, 3000);
        }

        // Cập nhật avatar bằng URL
        function updateAvatarByUrl(url) {
          if (url) {
            document.getElementById('profile_avatar_input').value = url;
            document.getElementById('avatarPreview').innerHTML = '<img src="' + url + '" class="w-full h-full object-cover" onerror="this.src=\'https://placehold.co/128x128?text=Avatar\'">';
            document.getElementById('liveAvatarPreview').src = url;
            showToast('URL avatar đã được cập nhật!', 'success');
          }
        }

        // Thêm nút dán URL avatar (tùy chọn)
        function addAvatarUrlInput() {
          const avatarCol = document.querySelector('#profile .grid-cols-1');
          if (avatarCol && !document.getElementById('avatarUrlInput')) {
            const urlDiv = document.createElement('div');
            urlDiv.className = 'mt-3';
            urlDiv.innerHTML = `
            <label class="block text-xs text-gray-600 mb-1">Hoặc nhập URL avatar</label>
            <div class="flex gap-2">
                <input type="url" id="avatarUrlInput" placeholder="https://..." 
                       class="flex-1 px-3 py-2 rounded-lg text-sm border border-gray-200">
                <button type="button" onclick="updateAvatarByUrl(document.getElementById('avatarUrlInput').value)" 
                        class="bg-gray-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-gray-600">
                    <i class="fa-solid fa-link"></i>
                </button>
            </div>
        `;
            avatarCol.appendChild(urlDiv);
          }
        }

        // Gọi thêm input URL avatar
        addAvatarUrlInput();

        // ==================== BACKGROUND FUNCTIONS (SỬA LỖI PREVIEW) ====================

        // Upload file background
        function uploadBackgroundFile(input, type) {
          const file = input.files[0];
          if (!file) {
            showToastBg('Vui lòng chọn file', 'error');
            return;
          }

          // Kiểm tra định dạng
          const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
          if (!allowedTypes.includes(file.type)) {
            showToastBg('Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WebP)', 'error');
            input.value = '';
            return;
          }

          // Kiểm tra kích thước (tối đa 5MB)
          if (file.size > 5 * 1024 * 1024) {
            showToastBg('File quá lớn! Tối đa 5MB', 'error');
            input.value = '';
            return;
          }

          // Hiển thị loading
          const btn = input.nextElementSibling;
          const originalText = btn.innerHTML;
          btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang upload...';
          btn.disabled = true;

          // Tạo FormData
          const formData = new FormData();
          formData.append(type === 'desktop' ? 'bg_desktop' : 'bg_mobile', file);

          // Upload
          fetch('upload_handler.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              console.log('Upload response:', data); // Debug
              if (data.success) {
                if (type === 'desktop') {
                  document.getElementById('bg_url').value = data.path;
                  updateDesktopPreview(data.path);
                } else {
                  document.getElementById('bg_mobile_url').value = data.path;
                  updateMobilePreview(data.path);
                }
                showToastBg('Upload ảnh thành công!', 'success');
              } else {
                showToastBg('Lỗi: ' + data.error, 'error');
              }
            })
            .catch(error => {
              console.error('Fetch error:', error);
              showToastBg('Lỗi kết nối server', 'error');
            })
            .finally(() => {
              btn.innerHTML = originalText;
              btn.disabled = false;
              input.value = '';
            });
        }

        // Cập nhật preview desktop
        function updateDesktopPreview(path) {
          console.log('Update desktop preview:', path); // Debug
          const previewDiv = document.getElementById('bgPreviewDesktop');
          if (previewDiv) {
            const container = previewDiv.querySelector('.aspect-video');
            if (container) {
              container.innerHTML = `<img src="${path}?t=${Date.now()}" class="w-full h-full object-cover" 
                                   onerror="this.onerror=null; this.src='https://placehold.co/800x450?text=Load+Failed'">`;
            } else {
              // Nếu không tìm thấy .aspect-video, thay thế toàn bộ
              previewDiv.innerHTML = `
                <div class="relative aspect-video bg-gray-100">
                    <img src="${path}?t=${Date.now()}" class="w-full h-full object-cover" 
                         onerror="this.onerror=null; this.src='https://placehold.co/800x450?text=Load+Failed'">
                </div>
                <div class="p-2 text-xs text-gray-500 text-center border-t">
                    <i class="fa-regular fa-eye"></i> Preview Desktop
                </div>
            `;
            }
          }
        }

        // Cập nhật preview mobile (QUAN TRỌNG - ĐÃ SỬA)
        function updateMobilePreview(path) {
          console.log('Update mobile preview:', path); // Debug
          const previewDiv = document.getElementById('bgPreviewMobile');
          if (previewDiv) {
            // Tìm hoặc tạo container cho ảnh
            let imgContainer = previewDiv.querySelector('.bg-gray-100');
            if (!imgContainer) {
              imgContainer = previewDiv.querySelector('.aspect-\\[9\\/16\\]');
            }

            if (imgContainer) {
              imgContainer.innerHTML = `<img src="${path}?t=${Date.now()}" class="w-full h-full object-cover" 
                                       onerror="this.onerror=null; this.src='https://placehold.co/300x533?text=Load+Failed'">`;
            } else {
              // Thay thế toàn bộ preview nếu không tìm thấy container
              previewDiv.innerHTML = `
                <div class="relative aspect-[9/16] max-h-48 bg-gray-100 mx-auto">
                    <img src="${path}?t=${Date.now()}" class="w-full h-full object-cover" 
                         onerror="this.onerror=null; this.src='https://placehold.co/300x533?text=Load+Failed'">
                </div>
                <div class="p-2 text-xs text-gray-500 text-center border-t">
                    <i class="fa-regular fa-eye"></i> Preview Mobile
                </div>
            `;
            }
          }
        }

        // Preview khi nhập URL thủ công
        function previewBackgroundUrl() {
          const url = document.getElementById('bg_url').value;
          if (url) {
            updateDesktopPreview(url);
          }
        }

        function previewMobileBackgroundUrl() {
          const url = document.getElementById('bg_mobile_url').value;
          console.log('Manual URL input:', url); // Debug
          if (url) {
            updateMobilePreview(url);
          }
        }

        // Hàm riêng để test preview
        function testMobilePreview() {
          const mobileUrl = document.getElementById('bg_mobile_url').value;
          if (mobileUrl) {
            updateMobilePreview(mobileUrl);
          } else {
            showToastBg('Chưa có URL ảnh mobile', 'error');
          }
        }

        // Thêm nút test preview (tùy chọn)
        function addTestButton() {
          const mobileSection = document.querySelector('#bgPreviewMobile');
          if (mobileSection && !document.getElementById('testPreviewBtn')) {
            const testBtn = document.createElement('button');
            testBtn.id = 'testPreviewBtn';
            testBtn.type = 'button';
            testBtn.innerHTML = '<i class="fa-solid fa-refresh"></i> Refresh Preview';
            testBtn.className = 'text-xs text-blue-500 mt-2 hover:text-blue-700';
            testBtn.onclick = testMobilePreview;
            mobileSection.parentElement.appendChild(testBtn);
          }
        }

        // Toast notification
        function showToastBg(message, type) {
          let toast = document.getElementById('bgToast');
          if (toast) toast.remove();

          toast = document.createElement('div');
          toast.id = 'bgToast';
          toast.className = 'fixed bottom-20 left-1/2 transform -translate-x-1/2 z-50 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 text-sm';
          const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
          const iconColor = type === 'success' ? 'text-green-400' : 'text-red-400';
          toast.innerHTML = `<i class="fa-solid ${icon} ${iconColor}"></i><span>${message}</span>`;
          document.body.appendChild(toast);

          setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            setTimeout(() => toast.remove(), 500);
          }, 3000);
        }

        // Khởi tạo khi load trang
        document.addEventListener('DOMContentLoaded', function() {
          const bgUrl = document.getElementById('bg_url');
          const bgMobileUrl = document.getElementById('bg_mobile_url');

          if (bgUrl) {
            bgUrl.addEventListener('input', previewBackgroundUrl);
          }
          if (bgMobileUrl) {
            bgMobileUrl.addEventListener('input', previewMobileBackgroundUrl);
            // Trigger preview ngay khi load nếu có giá trị
            if (bgMobileUrl.value) {
              setTimeout(() => updateMobilePreview(bgMobileUrl.value), 500);
            }
          }

          // Thêm nút test preview
          addTestButton();
        });

        // ==================== SETTINGS FUNCTIONS ====================

        // Live preview cho Cài đặt chung
        function initSettingsPreview() {
          // Site name preview
          const siteNameInput = document.querySelector('input[name="site_name"]');
          if (siteNameInput) {
            siteNameInput.addEventListener('input', function() {
              const preview = document.getElementById('previewSiteName');
              if (preview) preview.textContent = this.value || 'Huutien Mods';
            });
          }

          // Brand preview
          const brandFirstInput = document.querySelector('input[name="brand_first"]');
          const brandSecondInput = document.querySelector('input[name="brand_second"]');

          function updateBrandPreview() {
            const preview = document.getElementById('previewBrand');
            if (preview) {
              const first = document.querySelector('input[name="brand_first"]')?.value || 'Huutien';
              const second = document.querySelector('input[name="brand_second"]')?.value || 'Mods';
              preview.innerHTML = `<span class="text-blue-600">${first}</span><span class="text-gray-800"> ${second}</span>`;
            }
          }

          if (brandFirstInput) brandFirstInput.addEventListener('input', updateBrandPreview);
          if (brandSecondInput) brandSecondInput.addEventListener('input', updateBrandPreview);

          // Footer preview
          const footerInput = document.querySelector('input[name="footer_text"]');
          if (footerInput) {
            footerInput.addEventListener('input', function() {
              const preview = document.getElementById('previewFooter');
              if (preview) preview.textContent = this.value || '@thanhtapcode';
            });
          }

          // Description preview
          const descTextarea = document.querySelector('textarea[name="site_description"]');
          if (descTextarea) {
            descTextarea.addEventListener('input', function() {
              const preview = document.getElementById('previewDescription');
              if (preview) {
                let text = this.value || 'Chưa có mô tả';
                if (text.length > 100) text = text.substring(0, 100) + '...';
                preview.textContent = text;
              }
            });
          }
        }

        // Khởi tạo preview khi load trang
        document.addEventListener('DOMContentLoaded', function() {
          initSettingsPreview();
        });

        // Active menu highlight khi scroll
        function highlightActiveMenuMobile() {
          const sections = ['settings', 'profile', 'categories', 'social', 'music', 'navbar', 'sidebar_menu', 'background_section'];
          let current = '';

          for (const section of sections) {
            const element = document.getElementById(section);
            if (element) {
              const rect = element.getBoundingClientRect();
              if (rect.top <= 150 && rect.bottom >= 150) {
                current = section;
                break;
              }
            }
          }

          // Cập nhật active cho menu mobile
          document.querySelectorAll('.sidebar-mobile .menu-item').forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('data-section') === current) {
              item.classList.add('active');
            }
          });
        }

        // Thêm event listener
        window.addEventListener('scroll', highlightActiveMenuMobile);
        window.addEventListener('load', highlightActiveMenuMobile);

        // Cập nhật active khi scrollToSection
        const originalScrollToSection = window.scrollToSection;
        window.scrollToSection = function(sectionId) {
          if (originalScrollToSection) originalScrollToSection(sectionId);
          setTimeout(highlightActiveMenuMobile, 100);
        };

        // Active menu highlight cho desktop sidebar
        // ==================== DESKTOP SIDEBAR ACTIVE MENU ====================

        function highlightActiveMenu() {
          const sections = ['settings', 'profile', 'categories', 'social', 'music', 'navbar', 'sidebar_menu', 'background_section'];
          let current = '';

          for (const section of sections) {
            const element = document.getElementById(section);
            if (element) {
              const rect = element.getBoundingClientRect();
              if (rect.top <= 150 && rect.bottom >= 150) {
                current = section;
                break;
              }
            }
          }

          document.querySelectorAll('.sidebar-desktop .menu-item').forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('data-section') === current) {
              item.classList.add('active');
            }
          });
        }

        // Thêm event listener
        document.addEventListener('DOMContentLoaded', function() {
          highlightActiveMenu();
          window.addEventListener('scroll', highlightActiveMenu);
        });

        // Hàm scrollToSection nếu chưa có
        function scrollToSection(sectionId) {
          const element = document.getElementById(sectionId);
          if (element) {
            const offset = 80;
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;
            window.scrollTo({
              top: offsetPosition,
              behavior: 'smooth'
            });
          }
        }
      </script>

      <script>
        // ÉP HIỂN THỊ SIDEBAR TRÊN DESKTOP
        (function() {
          var sidebar = document.getElementById('desktopSidebar');
          var mainContent = document.querySelector('.main-content');

          // Bỏ qua kiểm tra kích thước, cứ hiển thị trên Windows
          var isWindows = navigator.platform.indexOf('Win') !== -1;

          if (isWindows) {
            if (sidebar) sidebar.style.display = 'block';
            if (mainContent) mainContent.style.marginLeft = '280px';
            console.log('Windows mode - Ép hiển thị sidebar');
          } else {
            // Chỉ kiểm tra kích thước khi không phải Windows
            if (window.innerWidth >= 1024) {
              if (sidebar) sidebar.style.display = 'block';
              if (mainContent) mainContent.style.marginLeft = '280px';
            }
          }
        })();
      </script>
</body>

</html>
