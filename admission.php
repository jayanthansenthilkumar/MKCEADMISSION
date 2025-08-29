<?php
session_start();
if(!isset($_SESSION['username']) && !isset($_SESSION['id'])){
    header("Location: index.php");
    exit;
}

// Use id if username is not set
$display_name = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['id'];

// Get faculty info
include 'config.php';
$faculty_id = $_SESSION['id'];
$faculty_sql = "SELECT name, dept FROM faculty WHERE id = '$faculty_id'";
$faculty_result = $conn->query($faculty_sql);
$faculty_info = $faculty_result->fetch_assoc();

// Get academic years for form
$ayear_options = '';
$ayear_sql = "SELECT * FROM ayear ORDER BY id DESC";
$ayear_result = $conn->query($ayear_sql);
if($ayear_result) {
    while($row = $ayear_result->fetch_assoc()) {
        $ayear_options .= "<option value='".$row['id']."'>".$row['ayear']."</option>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MKCE | ADMISSION</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* MKCE Admission Portal - Dashboard Design */
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Inter', sans-serif; 
        }

        :root {
            --primary-color: #667eea;
            --primary-dark: #5a67d8;
            --secondary-color: #764ba2;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --header-height: 70px;
            --border-radius: 16px;
            --shadow-light: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-medium: 0 8px 30px rgba(0,0,0,0.12);
            --shadow-heavy: 0 20px 40px rgba(0,0,0,0.15);
        }

        body, html { 
            height: 100%; 
            width: 100%; 
            background: var(--light-color);
            overflow-x: hidden;
        }

        /* Dashboard Layout */
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
            background: var(--light-color);
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(145deg, #1e293b 0%, #334155 100%);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-medium);
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 800;
            color: white;
        }

        .logo i {
            font-size: 32px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: rgba(255,255,255,0.1);
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin: 4px 16px;
        }

        .nav-item.active .nav-link {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            cursor: pointer;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(4px);
        }

        .nav-link i {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding: 12px;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .user-role {
            font-size: 12px;
            color: rgba(255,255,255,0.7);
        }

        .logout-btn {
            width: 100%;
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            border-color: rgba(239, 68, 68, 0.5);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            background: var(--light-color);
        }

        .sidebar.collapsed + .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Top Header */
        .top-header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: var(--shadow-light);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--dark-color);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background: var(--light-color);
        }

        .header-left h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark-color);
        }

        .header-right {
            display: flex;
            align-items: center;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--dark-color);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            background: var(--light-color);
        }

        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: var(--danger-color);
            color: white;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .user-dropdown {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 12px;
            transition: all 0.3s ease;
            color: var(--dark-color);
            font-weight: 500;
        }

        .user-btn:hover {
            background: var(--light-color);
        }

        .user-avatar-small {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        /* Content Area */
        .content-area {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .welcome-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 2;
        }

        .welcome-text h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-text p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 16px;
        }

        .welcome-date {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            opacity: 0.8;
        }

        .welcome-illustration {
            font-size: 80px;
            opacity: 0.3;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Dashboard Stats Grid */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 24px;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .stat-icon.primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        .stat-icon.success {
            background: linear-gradient(135deg, var(--success-color), #059669);
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
        }

        .stat-icon.info {
            background: linear-gradient(135deg, var(--info-color), #2563eb);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }

        .stat-change {
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change.positive {
            color: var(--success-color);
        }

        .stat-change.negative {
            color: var(--danger-color);
        }

        /* Quick Actions */
        .quick-actions {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: var(--primary-color);
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .action-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            text-align: center;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid #e2e8f0;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            border-color: var(--primary-color);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin: 0 auto 16px;
        }

        .action-card h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .action-card p {
            font-size: 14px;
            color: #64748b;
        }

        /* Content Sections */
        .content-section {
            background: white;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: var(--shadow-light);
            border: 1px solid #e2e8f0;
        }

        .section-header {
            padding: 24px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-header h3 i {
            color: var(--primary-color);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-body {
            padding: 30px;
        }

        /* Form Styles */
        .form-section {
            margin-bottom: 30px;
            padding: 24px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid var(--primary-color);
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-section-title i {
            color: var(--primary-color);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 16px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .btn-warning {
            background: var(--warning-color);
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
        }

        .data-table th {
            background: #f9fafb;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 14px;
        }

        .data-table tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.001);
            transition: all 0.2s ease;
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-confirmed {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .status-warning {
            background: rgba(251, 191, 36, 0.1);
            color: #d97706;
            border: 1px solid rgba(251, 191, 36, 0.2);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
            color: white;
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        .btn-action.btn-primary {
            background: var(--primary-color);
        }

        .btn-action.btn-success {
            background: var(--success-color);
        }

        .btn-action.btn-danger {
            background: var(--danger-color);
        }

        .btn-action.btn-warning {
            background: var(--warning-color);
        }

        /* Search Box */
        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 14px;
        }

        .search-box .form-control {
            padding-left: 36px;
        }

        /* Badge */
        .badge {
            background: var(--primary-color);
            color: white;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
            margin-left: 8px;
        }

        /* Activity List */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid var(--primary-color);
        }

        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: white;
            flex-shrink: 0;
        }

        .activity-icon.confirmed {
            background: var(--success-color);
        }

        .activity-content {
            flex: 1;
        }

        .activity-content p {
            margin: 0 0 4px 0;
            color: var(--dark-color);
            font-size: 14px;
        }

        .activity-time {
            font-size: 12px;
            color: #64748b;
        }

        /* Recent Activity */
        .recent-activity {
            margin-bottom: 30px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-dialog {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-heavy);
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 24px 30px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.1);
        }

        .modal-body {
            padding: 30px;
        }

        .modal-footer {
            padding: 20px 30px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: #f8fafc;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .dashboard-stats {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .welcome-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .welcome-illustration {
                font-size: 60px;
            }
            
            .dashboard-stats {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .actions-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 12px;
            }
            
            .header-actions {
                gap: 12px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .content-area {
                padding: 20px;
            }

            .section-header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 480px) {
            .welcome-card {
                padding: 20px;
            }
            
            .welcome-text h2 {
                font-size: 24px;
            }
            
            .stat-card {
                padding: 16px;
            }
            
            .action-card {
                padding: 16px;
            }
            
            .action-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .section-header {
                padding: 16px 20px;
            }

            .section-body {
                padding: 20px;
            }

            .modal-dialog {
                width: 95%;
                margin: 20px;
            }
        }

        /* Loading Spinner */
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 50%;
            border-top: 2px solid var(--primary-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-20 { margin-bottom: 20px; }
        .mt-20 { margin-top: 20px; }
        .hidden { display: none; }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .items-center { align-items: center; }
        .gap-10 { gap: 10px; }
        .gap-15 { gap: 15px; }
        .gap-20 { gap: 20px; }
        .full-width { grid-column: 1 / -1; }

        /* Analytics Components */
        .analytics-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .analytics-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 24px;
            box-shadow: var(--shadow-light);
            border: 1px solid #e2e8f0;
        }

        .analytics-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .analytics-header h4 {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
        }

        .analytics-header i {
            color: var(--primary-color);
            font-size: 20px;
        }

        .analytics-content {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .summary-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }

        .summary-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .dept-stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .dept-stat-item:last-child {
            border-bottom: none;
        }

        .dept-name {
            font-size: 14px;
            color: var(--dark-color);
            font-weight: 500;
        }

        .dept-count {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-color);
            background: rgba(102, 126, 234, 0.1);
            padding: 4px 8px;
            border-radius: 8px;
        }

        .trend-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .trend-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }

        .trend-value {
            font-size: 14px;
            font-weight: 600;
        }

        .trend-value.positive {
            color: var(--success-color);
        }

        .trend-value.negative {
            color: var(--danger-color);
        }

        .trend-value.neutral {
            color: #64748b;
        }

        .export-section {
            margin-top: 30px;
            padding: 24px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .export-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 14px;
            color: var(--dark-color);
            font-weight: 500;
        }

        .no-activity {
            text-align: center;
            color: #64748b;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>MKCE</span>
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item active">
                    <a href="#" class="nav-link" data-tab="dashboard-tab">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-tab="new-admission-tab">
                        <i class="fas fa-user-plus"></i>
                        <span>New Admission</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-tab="admissions-tab">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Manage Admissions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-tab="students-tab">
                        <i class="fas fa-users"></i>
                        <span>Students Database</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-tab="reports-tab">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports & Analytics</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo $faculty_info['name']; ?></div>
                    <div class="user-role"><?php echo $faculty_info['dept']; ?></div>
                </div>
            </div>
            <button class="logout-btn" onclick="confirmLogout()">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Admission Management System</h1>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <button class="notification-btn" onclick="showNotifications()">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="user-dropdown">
                        <button class="user-btn" onclick="toggleUserDropdown()">
                            <div class="user-avatar-small">
                                <?php echo strtoupper(substr($faculty_info['name'], 0, 1)); ?>
                            </div>
                            <span><?php echo $faculty_info['name']; ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="content-area">
            <!-- Dashboard Tab -->
            <div id="dashboard-tab" class="tab-content active">
                <!-- Welcome Card -->
                <div class="welcome-card">
                    <div class="welcome-content">
                        <div class="welcome-text">
                            <h2>Welcome back, <?php echo $faculty_info['name']; ?>!</h2>
                            <p>Here's what's happening with admissions today</p>
                            <div class="welcome-date">
                                <i class="fas fa-calendar-alt"></i>
                                <span><?php echo date('l, F d, Y'); ?></span>
                            </div>
                        </div>
                        <div class="welcome-illustration">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon primary">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stat-value" data-stat="total_applications" id="totalApplications">0</div>
                        <div class="stat-label">Total Applications</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+12% from last month</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon warning">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="stat-value" data-stat="pending_review" id="pendingReview">0</div>
                        <div class="stat-label">Pending Review</div>
                        <div class="stat-change negative">
                            <i class="fas fa-arrow-down"></i>
                            <span>-5% from last week</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="stat-value" data-stat="confirmed_students" id="confirmedStudents">0</div>
                        <div class="stat-label">Confirmed Students</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+8% from last month</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon info">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                        <div class="stat-value" data-stat="total_students" id="totalStudents">0</div>
                        <div class="stat-label">Total Students</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>Active students</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h3 class="section-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h3>
                    <div class="actions-grid">
                        <div class="action-card" data-action="new-admission">
                            <div class="action-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h3>Add New Admission</h3>
                            <p>Register a new student admission</p>
                        </div>
                        <div class="action-card" data-action="confirmed-students">
                            <div class="action-icon">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <h3>Review Admissions</h3>
                            <p>Review pending applications</p>
                        </div>
                        <div class="action-card" data-action="export-data">
                            <div class="action-icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <h3>Export Data</h3>
                            <p>Download admission reports</p>
                        </div>
                        <div class="action-card" data-action="system-settings">
                            <div class="action-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <h3>Settings</h3>
                            <p>Configure system settings</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="recent-activity">
                    <h3 class="section-title">
                        <i class="fas fa-clock"></i>
                        Recent Activity
                    </h3>
                    <div class="content-section">
                        <div class="section-body">
                            <div class="activity-list" id="recentActivityList">
                                <div class="activity-item">
                                    <div class="activity-icon confirmed">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="activity-content">
                                        <p><strong>System Ready:</strong> Dashboard loaded successfully</p>
                                        <span class="activity-time">Just now</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Admission Tab -->
            <div id="new-admission-tab" class="tab-content">
                <div class="content-section">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-user-plus"></i>
                            New Student Admission
                        </h3>
                    </div>
                    <div class="section-body">
                        <form id="admissionForm" class="admission-form">
                            <!-- Personal Information Section -->
                            <div class="form-section">
                                <h4 class="form-section-title">
                                    <i class="fas fa-user"></i>
                                    Personal Information
                                </h4>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="sid">Student ID *</label>
                                        <input type="text" id="sid" name="sid" class="form-control" required 
                                               placeholder="e.g., 25MKCECS001">
                                    </div>
                                    <div class="form-group">
                                        <label for="fname">First Name *</label>
                                        <input type="text" id="fname" name="fname" class="form-control" required 
                                               placeholder="Enter first name">
                                    </div>
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input type="text" id="lname" name="lname" class="form-control" 
                                               placeholder="Enter last name">
                                    </div>
                                    <div class="form-group">
                                        <label for="dob">Date of Birth</label>
                                        <input type="date" id="dob" name="dob" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select id="gender" name="gender" class="form-control">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                        <input type="tel" id="mobile" name="mobile" class="form-control" 
                                               placeholder="+91 9876543210">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" id="email" name="email" class="form-control" 
                                               placeholder="student@example.com">
                                    </div>
                                </div>
                            </div>

                            <!-- Academic Information Section -->
                            <div class="form-section">
                                <h4 class="form-section-title">
                                    <i class="fas fa-graduation-cap"></i>
                                    Academic Information
                                </h4>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="programme">Programme *</label>
                                        <select id="programme" name="programme" class="form-control" required>
                                            <option value="">Select Programme</option>
                                            <option value="B.E">Bachelor of Engineering (B.E)</option>
                                            <option value="B.Tech">Bachelor of Technology (B.Tech)</option>
                                            <option value="M.E">Master of Engineering (M.E)</option>
                                            <option value="M.Tech">Master of Technology (M.Tech)</option>
                                            <option value="MBA">Master of Business Administration (MBA)</option>
                                            <option value="MCA">Master of Computer Applications (MCA)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="department">Department *</label>
                                        <select id="department" name="department" class="form-control" required>
                                            <option value="">Select Department</option>
                                            <option value="Computer Science and Engineering">Computer Science and Engineering</option>
                                            <option value="Electronics and Communication Engineering">Electronics and Communication Engineering</option>
                                            <option value="Electrical and Electronics Engineering">Electrical and Electronics Engineering</option>
                                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                                            <option value="Civil Engineering">Civil Engineering</option>
                                            <option value="Information Technology">Information Technology</option>
                                            <option value="Aeronautical Engineering">Aeronautical Engineering</option>
                                            <option value="Automobile Engineering">Automobile Engineering</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="batch">Batch *</label>
                                        <select id="batch" name="batch" class="form-control" required>
                                            <option value="">Select Batch</option>
                                            <option value="2025-2029">2025-2029</option>
                                            <option value="2026-2030">2026-2030</option>
                                            <option value="2027-2031">2027-2031</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="doadmission">Date of Admission *</label>
                                        <input type="date" id="doadmission" name="doadmission" class="form-control" required
                                               value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="admission_category">Admission Category</label>
                                        <select id="admission_category" name="admission_category" class="form-control">
                                            <option value="General">General</option>
                                            <option value="OBC">OBC</option>
                                            <option value="SC">SC</option>
                                            <option value="ST">ST</option>
                                            <option value="Management">Management</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="admission_type">Admission Type</label>
                                        <select id="admission_type" name="admission_type" class="form-control">
                                            <option value="Regular">Regular</option>
                                            <option value="Lateral Entry">Lateral Entry</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="previous_education">Previous Education</label>
                                        <input type="text" id="previous_education" name="previous_education" class="form-control" 
                                               placeholder="e.g., 12th Science, Diploma">
                                    </div>
                                    <div class="form-group">
                                        <label for="marks_percentage">Previous Education Marks (%)</label>
                                        <input type="number" id="marks_percentage" name="marks_percentage" class="form-control" 
                                               min="0" max="100" step="0.01" placeholder="85.5">
                                    </div>
                                    <div class="form-group">
                                        <label for="ayear_id">Academic Year</label>
                                        <select id="ayear_id" name="ayear_id" class="form-control">
                                            <option value="">Select Academic Year</option>
                                            <?php
                                            $ayear_sql = "SELECT * FROM ayear ORDER BY id DESC";
                                            $ayear_result = $conn->query($ayear_sql);
                                            if($ayear_result) {
                                                while($row = $ayear_result->fetch_assoc()) {
                                                    echo "<option value='".$row['id']."'>".$row['ayear']."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" onclick="resetAdmissionForm()">
                                    <i class="fas fa-undo"></i>
                                    Reset Form
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Save Admission
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Admissions Management Tab -->
            <div id="admissions-tab" class="tab-content">
                <div class="content-section">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-clipboard-list"></i>
                            Manage Admissions
                            <span class="badge" id="admissionsBadge">0</span>
                        </h3>
                        <div class="header-actions">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="admissionSearch" placeholder="Search admissions..." class="form-control">
                            </div>
                            <button class="btn btn-secondary" onclick="refreshAdmissions()">
                                <i class="fas fa-sync-alt"></i>
                                Refresh
                            </button>
                            <button class="btn btn-primary" onclick="exportData('admissions')">
                                <i class="fas fa-download"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <div class="section-body">
                        <!-- Admissions Table -->
                        <div class="table-responsive">
                            <table class="data-table" id="admissionsTable">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Programme</th>
                                        <th>Department</th>
                                        <th>Batch</th>
                                        <th>Admission Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Database Tab -->
            <div id="students-tab" class="tab-content">
                <div class="content-section">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-users"></i>
                            Students Database
                            <span class="badge" id="studentsBadge">0</span>
                        </h3>
                        <div class="header-actions">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="studentSearch" placeholder="Search students..." class="form-control">
                            </div>
                            <button class="btn btn-secondary" onclick="refreshStudents()">
                                <i class="fas fa-sync-alt"></i>
                                Refresh
                            </button>
                            <button class="btn btn-primary" onclick="exportData('students')">
                                <i class="fas fa-download"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <div class="section-body">
                        <!-- Students Table -->
                        <div class="table-responsive">
                            <table class="data-table" id="studentsTable">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Programme</th>
                                        <th>Department</th>
                                        <th>Batch</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Admission Stage</th>
                                        <th>Profile Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports & Analytics Tab -->
            <div id="reports-tab" class="tab-content">
                <div class="content-section">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-chart-bar"></i>
                            Reports & Analytics
                        </h3>
                        <div class="header-actions">
                            <select id="reportPeriod" class="form-control">
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month" selected>This Month</option>
                                <option value="year">This Year</option>
                            </select>
                            <button class="btn btn-primary" onclick="generateReport()">
                                <i class="fas fa-chart-line"></i>
                                Generate Report
                            </button>
                        </div>
                    </div>
                    <div class="section-body">
                        <!-- Analytics Overview -->
                        <div class="analytics-overview">
                            <div class="analytics-card">
                                <div class="analytics-header">
                                    <h4>Admission Summary</h4>
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <div class="analytics-content">
                                    <div class="summary-item">
                                        <span class="summary-label">Total Applications:</span>
                                        <span class="summary-value" id="reportTotalApplications">0</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Confirmed Students:</span>
                                        <span class="summary-value" id="reportConfirmedStudents">0</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Rejection Rate:</span>
                                        <span class="summary-value" id="reportRejectionRate">0%</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Popular Department:</span>
                                        <span class="summary-value" id="reportPopularDept">-</span>
                                    </div>
                                </div>
                            </div>

                            <div class="analytics-card">
                                <div class="analytics-header">
                                    <h4>Department Breakdown</h4>
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="analytics-content">
                                    <div class="department-stats" id="departmentStats">
                                        <!-- Department statistics will be loaded here -->
                                    </div>
                                </div>
                            </div>

                            <div class="analytics-card">
                                <div class="analytics-header">
                                    <h4>Recent Trends</h4>
                                    <i class="fas fa-trend-up"></i>
                                </div>
                                <div class="analytics-content">
                                    <div class="trend-item">
                                        <span class="trend-label">This Month:</span>
                                        <span class="trend-value positive">+15%</span>
                                    </div>
                                    <div class="trend-item">
                                        <span class="trend-label">This Week:</span>
                                        <span class="trend-value positive">+8%</span>
                                    </div>
                                    <div class="trend-item">
                                        <span class="trend-label">Today:</span>
                                        <span class="trend-value neutral">0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Export Options -->
                        <div class="export-section">
                            <h4 class="section-title">
                                <i class="fas fa-download"></i>
                                Export Reports
                            </h4>
                            <div class="export-options">
                                <button class="btn btn-primary" onclick="exportData('admissions')">
                                    <i class="fas fa-file-csv"></i>
                                    Export Admissions
                                </button>
                                <button class="btn btn-success" onclick="exportData('students')">
                                    <i class="fas fa-file-excel"></i>
                                    Export Students
                                </button>
                                <button class="btn btn-info" onclick="exportData('all')">
                                    <i class="fas fa-file-archive"></i>
                                    Export All Data
                                </button>
                                <button class="btn btn-warning" onclick="generatePDFReport()">
                                    <i class="fas fa-file-pdf"></i>
                                    PDF Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Student Profile Modal -->
<div id="studentProfileModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>
                <i class="fas fa-user"></i>
                Student Profile
            </h3>
            <button type="button" class="modal-close" onclick="closeStudentProfileModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="profile-sections" id="studentProfileContent">
                <!-- Profile content will be loaded here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeStudentProfileModal()">
                Close
            </button>
            <button type="button" class="btn btn-primary" onclick="editStudentProfile()">
                <i class="fas fa-edit"></i>
                Edit Profile
            </button>
        </div>
    </div>
</div>

<!-- Complete Student Profile Modal -->
<div id="completeProfileModal" class="modal">
    <div class="modal-dialog" style="max-width: 1200px;">
        <div class="modal-header">
            <h3>
                <i class="fas fa-user-graduate"></i>
                Complete Student Profile
            </h3>
            <button type="button" class="modal-close" onclick="closeCompleteProfileModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="completeProfileForm">
                <input type="hidden" id="complete_admission_id" name="admission_id">
                
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="complete_sid">Student ID</label>
                            <input type="text" id="complete_sid" name="sid" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="complete_fname">First Name</label>
                            <input type="text" id="complete_fname" name="fname" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="complete_lname">Last Name</label>
                            <input type="text" id="complete_lname" name="lname" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="complete_dob">Date of Birth *</label>
                            <input type="date" id="complete_dob" name="dob" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="complete_gender">Gender *</label>
                            <select id="complete_gender" name="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="complete_blood_group">Blood Group</label>
                            <select id="complete_blood_group" name="blood_group" class="form-control">
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="complete_religion">Religion</label>
                            <input type="text" id="complete_religion" name="religion" class="form-control" placeholder="e.g., Hindu, Christian, Muslim">
                        </div>
                        <div class="form-group">
                            <label for="complete_caste">Caste/Community</label>
                            <input type="text" id="complete_caste" name="caste" class="form-control" placeholder="e.g., General, OBC, SC, ST">
                        </div>
                        <div class="form-group">
                            <label for="complete_nationality">Nationality</label>
                            <input type="text" id="complete_nationality" name="nationality" class="form-control" value="Indian">
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-phone"></i>
                        Contact Information
                    </h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="complete_mobile">Mobile Number *</label>
                            <input type="tel" id="complete_mobile" name="mobile" class="form-control" required placeholder="+91 9876543210">
                        </div>
                        <div class="form-group">
                            <label for="complete_email">Email Address *</label>
                            <input type="email" id="complete_email" name="email" class="form-control" required placeholder="student@example.com">
                        </div>
                        <div class="form-group full-width">
                            <label for="complete_address">Complete Address *</label>
                            <textarea id="complete_address" name="address" class="form-control" rows="3" required placeholder="House/Flat No., Street, Area, Landmark"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="complete_city">City</label>
                            <input type="text" id="complete_city" name="city" class="form-control" placeholder="City">
                        </div>
                        <div class="form-group">
                            <label for="complete_state">State</label>
                            <input type="text" id="complete_state" name="state" class="form-control" placeholder="State">
                        </div>
                        <div class="form-group">
                            <label for="complete_pincode">PIN Code *</label>
                            <input type="text" id="complete_pincode" name="pincode" class="form-control" required placeholder="600001">
                        </div>
                        <div class="form-group">
                            <label for="complete_country">Country</label>
                            <input type="text" id="complete_country" name="country" class="form-control" value="India">
                        </div>
                    </div>
                </div>

                <!-- Guardian Information Section -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-users"></i>
                        Guardian Information
                    </h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="complete_father_name">Father's Name *</label>
                            <input type="text" id="complete_father_name" name="father_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="complete_mother_name">Mother's Name</label>
                            <input type="text" id="complete_mother_name" name="mother_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="complete_guardian_mobile">Guardian Mobile</label>
                            <input type="tel" id="complete_guardian_mobile" name="guardian_mobile" class="form-control" placeholder="+91 9876543210">
                        </div>
                        <div class="form-group">
                            <label for="complete_guardian_email">Guardian Email</label>
                            <input type="email" id="complete_guardian_email" name="guardian_email" class="form-control" placeholder="parent@example.com">
                        </div>
                        <div class="form-group">
                            <label for="complete_guardian_occupation">Guardian Occupation</label>
                            <input type="text" id="complete_guardian_occupation" name="guardian_occupation" class="form-control" placeholder="e.g., Engineer, Teacher, Business">
                        </div>
                        <div class="form-group">
                            <label for="complete_annual_income">Annual Income (₹)</label>
                            <input type="number" id="complete_annual_income" name="annual_income" class="form-control" min="0" placeholder="500000">
                        </div>
                    </div>
                </div>

                <!-- Academic Background Section -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-graduation-cap"></i>
                        Academic Background
                    </h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="complete_tenth_board">10th Board</label>
                            <input type="text" id="complete_tenth_board" name="tenth_board" class="form-control" placeholder="e.g., CBSE, State Board">
                        </div>
                        <div class="form-group">
                            <label for="complete_tenth_year">10th Year of Passing</label>
                            <input type="number" id="complete_tenth_year" name="tenth_year" class="form-control" min="2000" max="2030" placeholder="2023">
                        </div>
                        <div class="form-group">
                            <label for="complete_tenth_percentage">10th Percentage</label>
                            <input type="number" id="complete_tenth_percentage" name="tenth_percentage" class="form-control" min="0" max="100" step="0.01" placeholder="85.5">
                        </div>
                        <div class="form-group">
                            <label for="complete_twelfth_board">12th Board</label>
                            <input type="text" id="complete_twelfth_board" name="twelfth_board" class="form-control" placeholder="e.g., CBSE, State Board">
                        </div>
                        <div class="form-group">
                            <label for="complete_twelfth_year">12th Year of Passing</label>
                            <input type="number" id="complete_twelfth_year" name="twelfth_year" class="form-control" min="2000" max="2030" placeholder="2025">
                        </div>
                        <div class="form-group">
                            <label for="complete_twelfth_percentage">12th Percentage</label>
                            <input type="number" id="complete_twelfth_percentage" name="twelfth_percentage" class="form-control" min="0" max="100" step="0.01" placeholder="92.5">
                        </div>
                        <div class="form-group">
                            <label for="complete_entrance_exam">Entrance Exam</label>
                            <input type="text" id="complete_entrance_exam" name="entrance_exam" class="form-control" placeholder="e.g., JEE, NEET, TNEA">
                        </div>
                        <div class="form-group">
                            <label for="complete_entrance_score">Entrance Exam Score</label>
                            <input type="number" id="complete_entrance_score" name="entrance_score" class="form-control" min="0" placeholder="150">
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact Section -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Emergency Contact
                    </h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="complete_emergency_name">Emergency Contact Name</label>
                            <input type="text" id="complete_emergency_name" name="emergency_contact_name" class="form-control" placeholder="Name of emergency contact">
                        </div>
                        <div class="form-group">
                            <label for="complete_emergency_relation">Relation</label>
                            <input type="text" id="complete_emergency_relation" name="emergency_contact_relation" class="form-control" placeholder="e.g., Uncle, Aunt, Friend">
                        </div>
                        <div class="form-group">
                            <label for="complete_emergency_mobile">Emergency Contact Mobile</label>
                            <input type="tel" id="complete_emergency_mobile" name="emergency_contact_mobile" class="form-control" placeholder="+91 9876543210">
                        </div>
                    </div>
                </div>

                <!-- Medical Information Section -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-heartbeat"></i>
                        Medical Information
                    </h4>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="complete_medical_conditions">Medical Conditions (if any)</label>
                            <textarea id="complete_medical_conditions" name="medical_conditions" class="form-control" rows="2" placeholder="Any medical conditions, medications, or health issues"></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label for="complete_allergies">Allergies (if any)</label>
                            <textarea id="complete_allergies" name="allergies" class="form-control" rows="2" placeholder="Food allergies, drug allergies, etc."></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeCompleteProfileModal()">
                Cancel
            </button>
            <button type="button" class="btn btn-primary" onclick="saveCompleteProfile()">
                <i class="fas fa-save"></i>
                Complete Profile
            </button>
        </div>
    </div>
</div>

<script>
// Dashboard functionality
$(document).ready(function() {
    // Initialize dashboard
    initializeDashboard();
    
    // Load initial data
    loadDashboardStats();
    loadAdmissions();
    
    // Handle admission form submission
    $('#admissionForm').on('submit', function(e) {
        e.preventDefault();
        saveAdmission();
    });
    
    // Handle search inputs
    $('#admissionSearch').on('input', function() {
        const searchTerm = $(this).val();
        loadAdmissions(searchTerm);
    });
    
    $('#studentSearch').on('input', function() {
        const searchTerm = $(this).val();
        loadStudents(searchTerm);
    });
    
    // Handle tab switching
    $('.nav-link').on('click', function(e) {
        e.preventDefault();
        const tabId = $(this).data('tab');
        switchTab(tabId);
        $(this).closest('.nav-item').addClass('active').siblings().removeClass('active');
    });
    
    // Handle quick actions
    $('.action-card').on('click', function() {
        const action = $(this).data('action');
        handleQuickAction(action);
    });
});

function initializeDashboard() {
    // Initialize any dashboard components
    console.log('Dashboard initialized');
    
    // Set up periodic refresh
    setInterval(function() {
        if($('#dashboard-tab').hasClass('active')) {
            loadDashboardStats();
        }
    }, 30000); // Refresh every 30 seconds
}

function loadDashboardStats() {
    $.get('api.php?action=get_dashboard_stats')
    .done(function(response) {
        if(response.success) {
            updateStatsDisplay(response.data);
            updateRecentActivity(response.data.recent_activity);
        }
    })
    .fail(function() {
        console.error('Failed to load dashboard statistics');
    });
}

function updateStatsDisplay(stats) {
    $('#totalApplications').text(stats.total_applications || 0);
    $('#pendingReview').text(stats.pending_review || 0);
    $('#confirmedStudents').text(stats.confirmed_students || 0);
    $('#totalStudents').text(stats.total_students || 0);
}

function updateRecentActivity(activities) {
    const activityList = $('#recentActivityList');
    activityList.empty();
    
    if(activities && activities.length > 0) {
        activities.forEach(function(activity) {
            const activityItem = `
                <div class="activity-item">
                    <div class="activity-icon ${activity.type}">
                        <i class="fas fa-${activity.icon}"></i>
                    </div>
                    <div class="activity-content">
                        <p>${activity.message}</p>
                        <span class="activity-time">${formatDateTime(activity.date)}</span>
                    </div>
                </div>
            `;
            activityList.append(activityItem);
        });
    } else {
        activityList.append('<div class="no-activity">No recent activity</div>');
    }
}

function loadAdmissions(search = '') {
    const params = search ? `?action=get_admissions&search=${encodeURIComponent(search)}` : '?action=get_admissions';
    
    $.get(`api.php${params}`)
    .done(function(response) {
        if(response.success) {
            updateAdmissionsTable(response.data);
            $('#admissionsBadge').text(response.data.length);
        }
    })
    .fail(function() {
        console.error('Failed to load admissions');
    });
}

function loadStudents(search = '') {
    const params = search ? `?action=get_students&search=${encodeURIComponent(search)}` : '?action=get_students';
    
    $.get(`api.php${params}`)
    .done(function(response) {
        if(response.success) {
            updateStudentsTable(response.data);
            $('#studentsBadge').text(response.data.length);
        }
    })
    .fail(function() {
        console.error('Failed to load students');
    });
}

function updateAdmissionsTable(admissions) {
    const tbody = $('#admissionsTable tbody');
    tbody.empty();
    
    admissions.forEach(function(admission) {
        const statusBadge = getStatusBadge(admission.status);
        const row = `
            <tr>
                <td>${admission.sid}</td>
                <td>${admission.fname} ${admission.lname || ''}</td>
                <td>${admission.programme}</td>
                <td>${admission.department}</td>
                <td>${admission.batch}</td>
                <td>${admission.doadmission}</td>
                <td>${statusBadge}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-primary" onclick="viewAdmission('${admission.id}')" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-success" onclick="confirmStudent('${admission.id}')" title="Confirm">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn-action btn-danger" onclick="rejectAdmission('${admission.id}')" title="Reject">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function updateStudentsTable(students) {
    const tbody = $('#studentsTable tbody');
    tbody.empty();
    
    students.forEach(function(student) {
        const profileBadge = getProfileStatusBadge(student.profile_status);
        const admissionStage = student.admission_stage || 'application_submitted';
        const stageBadge = getAdmissionStageBadge(admissionStage);
        
        // Generate action buttons based on admission stage
        let actionButtons = `
            <button class="btn-action btn-primary" onclick="viewStudentProfile('${student.sid}')" title="View Profile">
                <i class="fas fa-user"></i>
            </button>
        `;
        
        // Add Complete Profile button if student is confirmed but profile not complete
        if (admissionStage === 'confirmed_pending_details' || (admissionStage === 'confirmed' && student.profile_status !== 'complete')) {
            actionButtons += `
                <button class="btn-action btn-success" onclick="openCompleteProfileModal('${student.admission_id}')" title="Complete Profile">
                    <i class="fas fa-user-plus"></i>
                </button>
            `;
        }
        
        // Add edit button for completed profiles
        if (admissionStage === 'profile_completed' || student.profile_status === 'complete') {
            actionButtons += `
                <button class="btn-action btn-warning" onclick="editStudentDetails('${student.sid}')" title="Edit Details">
                    <i class="fas fa-edit"></i>
                </button>
            `;
        }
        
        const row = `
            <tr>
                <td>${student.sid}</td>
                <td>${student.fname} ${student.lname || ''}</td>
                <td>${student.programme}</td>
                <td>${student.department}</td>
                <td>${student.batch}</td>
                <td>${student.mobile || 'N/A'}</td>
                <td>${student.email || 'N/A'}</td>
                <td>${stageBadge}</td>
                <td>${profileBadge}</td>
                <td>
                    <div class="action-buttons">
                        ${actionButtons}
                    </div>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="status-badge status-pending">Pending</span>',
        'confirmed': '<span class="status-badge status-confirmed">Confirmed</span>',
        'rejected': '<span class="status-badge status-rejected">Rejected</span>'
    };
    return badges[status] || badges['pending'];
}

function getProfileStatusBadge(status) {
    const badges = {
        'Complete': '<span class="status-badge status-confirmed">Complete</span>',
        'Partial': '<span class="status-badge status-pending">Partial</span>',
        'Incomplete': '<span class="status-badge status-rejected">Incomplete</span>'
    };
    return badges[status] || badges['Incomplete'];
}

function getAdmissionStageBadge(stage) {
    const badges = {
        'application_submitted': '<span class="status-badge status-pending">Applied</span>',
        'confirmed_pending_details': '<span class="status-badge status-warning">Confirmed - Pending Details</span>',
        'profile_completed': '<span class="status-badge status-confirmed">Profile Complete</span>'
    };
    return badges[stage] || '<span class="status-badge status-rejected">Unknown</span>';
}

function saveAdmission() {
    const formData = new FormData($('#admissionForm')[0]);
    formData.append('action', 'save_admission');
    
    $.ajax({
        url: 'api.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if(response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Admission saved successfully',
                    timer: 2000
                });
                $('#admissionForm')[0].reset();
                loadAdmissions();
                loadDashboardStats();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to save admission'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Network error occurred'
            });
        }
    });
}

function confirmStudent(admissionId) {
    Swal.fire({
        title: 'Confirm Student?',
        text: 'This will move the admission to confirmed students and allow you to collect complete details',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Confirm',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Confirming...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.post('api.php', { action: 'confirm_student', admission_id: admissionId })
            .done(function(response) {
                if(response.success) {
                    Swal.fire({
                        title: 'Confirmed!',
                        text: 'Student has been confirmed. Now complete their profile.',
                        icon: 'success',
                        confirmButtonText: 'Complete Profile'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            openCompleteProfileModal(admissionId);
                        }
                    });
                    loadAdmissions();
                    loadStudents(); // Refresh students list
                    loadDashboardStats();
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            })
            .fail(function() {
                Swal.fire('Error!', 'Network error occurred', 'error');
            });
        }
    });
}

function rejectAdmission(admissionId) {
    Swal.fire({
        title: 'Reject Admission?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('api.php', { action: 'reject_admission', admission_id: admissionId })
            .done(function(response) {
                if(response.success) {
                    Swal.fire('Rejected!', 'Admission has been rejected', 'success');
                    loadAdmissions();
                    loadDashboardStats();
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            });
        }
    });
}

function viewStudentProfile(sid) {
    // Load student profile data and show modal
    $.get(`api.php?action=get_students&sid=${sid}`)
    .done(function(response) {
        if(response.success && response.data.length > 0) {
            const student = response.data[0];
            showStudentProfileModal(student);
        } else {
            Swal.fire('Error!', 'Student profile not found', 'error');
        }
    });
}

// Complete Profile Modal Functions
function openCompleteProfileModal(admissionId) {
    console.log('Opening complete profile modal for admission:', admissionId);
    
    // Get admission details first
    fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_student_details&admission_id=' + admissionId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const admission = data.data;
            
            // Populate the form with existing data
            document.getElementById('complete_admission_id').value = admissionId;
            document.getElementById('complete_sid').value = admission.sid || '';
            document.getElementById('complete_fname').value = admission.fname || '';
            document.getElementById('complete_lname').value = admission.lname || '';
            document.getElementById('complete_mobile').value = admission.mobile || '';
            document.getElementById('complete_email').value = admission.email || '';
            
            // Show the modal
            document.getElementById('completeProfileModal').classList.add('active');
        } else {
            console.error('Error fetching admission details:', data.message);
            Swal.fire('Error', 'Could not load admission details', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Network error occurred', 'error');
    });
}

function closeCompleteProfileModal() {
    document.getElementById('completeProfileModal').classList.remove('active');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('completeProfileModal');
    if (event.target === modal) {
        closeCompleteProfileModal();
    }
});

// Close modal with escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('completeProfileModal');
        if (modal.classList.contains('active')) {
            closeCompleteProfileModal();
        }
    }
});

function saveCompleteProfile() {
    const form = document.getElementById('completeProfileForm');
    const formData = new FormData(form);
    formData.append('action', 'complete_student_profile');

    // Validate required fields
    const requiredFields = ['dob', 'gender', 'mobile', 'email', 'address', 'pincode', 'father_name'];
    let isValid = true;
    
    for (const field of requiredFields) {
        const element = form.querySelector(`[name="${field}"]`);
        if (element && !element.value.trim()) {
            element.style.borderColor = '#dc3545';
            isValid = false;
        } else if (element) {
            element.style.borderColor = '#e9ecef';
        }
    }

    if (!isValid) {
        Swal.fire('Error', 'Please fill in all required fields', 'error');
        return;
    }

    // Show loading
    Swal.fire({
        title: 'Saving Profile...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success', 'Student profile completed successfully!', 'success');
            closeCompleteProfileModal();
            loadStudents(); // Refresh the students table
            loadDashboardStats(); // Update stats
        } else {
            Swal.fire('Error', data.message || 'Failed to save profile', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Network error occurred', 'error');
    });
}

function showStudentProfileModal(student) {
    const profileContent = `
        <div class="profile-section">
            <h4><i class="fas fa-user"></i> Personal Information</h4>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Student ID</div>
                    <div class="info-value">${student.sid}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value">${student.fname} ${student.lname || ''}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Mobile</div>
                    <div class="info-value">${student.mobile || 'Not provided'}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">${student.email || 'Not provided'}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Programme</div>
                    <div class="info-value">${student.programme}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Department</div>
                    <div class="info-value">${student.department}</div>
                </div>
            </div>
        </div>
    `;
    
    $('#studentProfileContent').html(profileContent);
    $('#studentProfileModal').addClass('active');
}

function closeStudentProfileModal() {
    $('#studentProfileModal').removeClass('active');
}

function editStudentDetails(sid) {
    // Load admission data for editing
    $.get(`api.php?action=get_admissions&sid=${sid}`)
    .done(function(response) {
        if(response.success && response.data.length > 0) {
            const admission = response.data[0];
            showStudentDetailsModal(admission);
        } else {
            Swal.fire('Error!', 'Admission record not found', 'error');
        }
    });
}

function showStudentDetailsModal(admission) {
    // Populate the form with admission data
    $('#student_admission_id').val(admission.id);
    $('#student_sid').val(admission.sid);
    $('#student_fname').val(admission.fname);
    $('#student_lname').val(admission.lname || '');
    $('#student_mobile').val(admission.mobile || '');
    $('#student_email').val(admission.email || '');
    $('#student_dob').val(admission.dob || '');
    $('#student_gender').val(admission.gender || '');
    
    // Show the modal
    $('#studentDetailsModal').addClass('active');
}

function closeStudentDetailsModal() {
    $('#studentDetailsModal').removeClass('active');
}

function saveStudentDetails() {
    const formData = new FormData($('#studentDetailsForm')[0]);
    formData.append('action', 'save_student_details');
    
    $.ajax({
        url: 'api.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if(response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Student details updated successfully',
                    timer: 2000
                });
                closeStudentDetailsModal();
                loadStudents();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Failed to update details'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Network error occurred'
            });
        }
    });
}

function switchTab(tabId) {
    // Hide all tabs
    $('.tab-content').removeClass('active');
    
    // Show selected tab
    $(`#${tabId}`).addClass('active');
    
    // Load tab-specific data
    if(tabId === 'admissions-tab') {
        loadAdmissions();
    } else if(tabId === 'students-tab') {
        loadStudents();
    } else if(tabId === 'dashboard-tab') {
        loadDashboardStats();
    } else if(tabId === 'reports-tab') {
        loadReportsData();
    }
}

function loadReportsData() {
    // Load analytics data for reports
    $.get('api.php?action=get_dashboard_stats')
    .done(function(response) {
        if(response.success) {
            updateReportsDisplay(response.data);
        }
    });
}

function updateReportsDisplay(data) {
    $('#reportTotalApplications').text(data.total_applications || 0);
    $('#reportConfirmedStudents').text(data.confirmed_students || 0);
    
    // Calculate rejection rate
    const rejectionRate = data.total_applications > 0 ? 
        Math.round(((data.total_applications - data.confirmed_students) / data.total_applications) * 100) : 0;
    $('#reportRejectionRate').text(rejectionRate + '%');
    
    // Update department stats
    const deptStats = $('#departmentStats');
    deptStats.empty();
    
    if(data.departments && data.departments.length > 0) {
        $('#reportPopularDept').text(data.departments[0].department);
        
        data.departments.forEach(function(dept) {
            const deptItem = `
                <div class="dept-stat-item">
                    <span class="dept-name">${dept.department}:</span>
                    <span class="dept-count">${dept.count}</span>
                </div>
            `;
            deptStats.append(deptItem);
        });
    }
}

function handleQuickAction(action) {
    switch(action) {
        case 'new-admission':
            switchTab('new-admission-tab');
            $('.nav-link[data-tab="new-admission-tab"]').closest('.nav-item').addClass('active').siblings().removeClass('active');
            break;
        case 'confirmed-students':
            switchTab('students-tab');
            $('.nav-link[data-tab="students-tab"]').closest('.nav-item').addClass('active').siblings().removeClass('active');
            break;
        case 'export-data':
            exportData('all');
            break;
        case 'system-settings':
            Swal.fire('Info', 'Settings panel coming soon!', 'info');
            break;
    }
}

function exportData(type) {
    window.open(`api.php?action=export_data&type=${type}`, '_blank');
}

function generateReport() {
    const period = $('#reportPeriod').val();
    // Implementation for generating custom reports
    Swal.fire('Success!', `Report for ${period} generated`, 'success');
}

function generatePDFReport() {
    Swal.fire('Info', 'PDF Report generation coming soon!', 'info');
}

function refreshAdmissions() {
    loadAdmissions();
    Swal.fire({
        icon: 'success',
        title: 'Refreshed!',
        text: 'Admissions data updated',
        timer: 1500,
        showConfirmButton: false
    });
}

function refreshStudents() {
    loadStudents();
    Swal.fire({
        icon: 'success',
        title: 'Refreshed!',
        text: 'Students data updated',
        timer: 1500,
        showConfirmButton: false
    });
}

function resetAdmissionForm() {
    $('#admissionForm')[0].reset();
    Swal.fire({
        icon: 'info',
        title: 'Form Reset',
        text: 'All fields have been cleared',
        timer: 1500,
        showConfirmButton: false
    });
}

// Utility functions
function toggleSidebar() {
    $('.sidebar').toggleClass('collapsed');
}

function confirmLogout() {
    Swal.fire({
        title: 'Logout?',
        text: 'Are you sure you want to logout?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Logout',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    });
}

function showNotifications() {
    Swal.fire({
        title: 'Notifications',
        html: '<div class="notification-list">No new notifications</div>',
        icon: 'info'
    });
}

function toggleUserDropdown() {
    // Implementation for user dropdown
    console.log('User dropdown toggled');
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    
    if(diff < 60000) return 'Just now';
    if(diff < 3600000) return Math.floor(diff / 60000) + ' minutes ago';
    if(diff < 86400000) return Math.floor(diff / 3600000) + ' hours ago';
    return Math.floor(diff / 86400000) + ' days ago';
}

// Mobile responsiveness
$(window).on('resize', function() {
    if ($(window).width() <= 768) {
        $('.sidebar').removeClass('open');
    }
});

// Mobile menu toggle
function toggleSidebar() {
    if ($(window).width() <= 768) {
        $('.sidebar').toggleClass('open');
    } else {
        $('.sidebar').toggleClass('collapsed');
    }
}
</script>
</body>
</html>
