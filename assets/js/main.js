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
    $.get('api/get_dashboard_stats.php')
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
    const params = search ? `?search=${encodeURIComponent(search)}` : '';
    
    $.get(`api/get_admissions.php${params}`)
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
    const params = search ? `?search=${encodeURIComponent(search)}` : '';
    
    $.get(`api/get_students_data.php${params}`)
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
        const row = `
            <tr>
                <td>${student.sid}</td>
                <td>${student.fname} ${student.lname || ''}</td>
                <td>${student.programme}</td>
                <td>${student.department}</td>
                <td>${student.batch}</td>
                <td>${student.mobile}</td>
                <td>${student.email}</td>
                <td>${profileBadge}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-primary" onclick="viewStudentProfile('${student.sid}')" title="View Profile">
                            <i class="fas fa-user"></i>
                        </button>
                        <button class="btn-action btn-warning" onclick="editStudentDetails('${student.sid}')" title="Edit Details">
                            <i class="fas fa-edit"></i>
                        </button>
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

function saveAdmission() {
    const formData = new FormData($('#admissionForm')[0]);
    
    $.ajax({
        url: 'api/save_admission.php',
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
        text: 'This will move the admission to confirmed students',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Confirm',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('api/confirm_student.php', { admission_id: admissionId })
            .done(function(response) {
                if(response.success) {
                    Swal.fire('Confirmed!', 'Student has been confirmed', 'success');
                    loadAdmissions();
                    loadDashboardStats();
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
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
            $.post('api/reject_admission.php', { admission_id: admissionId })
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
    $.get(`api/get_students.php?sid=${sid}`)
    .done(function(response) {
        if(response.success && response.data.length > 0) {
            const student = response.data[0];
            showStudentProfileModal(student);
        } else {
            Swal.fire('Error!', 'Student profile not found', 'error');
        }
    });
}

function showStudentProfileModal(student) {
    const profileContent = `
        <div class="profile-section">
            <h4><i class="fas fa-user"></i> Personal Information</h4>
            <div class="profile-grid">
                <div class="profile-item"><strong>Student ID:</strong> ${student.sid}</div>
                <div class="profile-item"><strong>Name:</strong> ${student.fname} ${student.lname || ''}</div>
                <div class="profile-item"><strong>Mobile:</strong> ${student.mobile || 'Not provided'}</div>
                <div class="profile-item"><strong>Email:</strong> ${student.email || 'Not provided'}</div>
                <div class="profile-item"><strong>Programme:</strong> ${student.programme}</div>
                <div class="profile-item"><strong>Department:</strong> ${student.department}</div>
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
    $.get(`api/get_admissions.php?sid=${sid}`)
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
    
    // Show the modal
    $('#studentDetailsModal').addClass('active');
}

function closeStudentDetailsModal() {
    $('#studentDetailsModal').removeClass('active');
}

function saveStudentDetails() {
    const formData = new FormData($('#studentDetailsForm')[0]);
    
    $.ajax({
        url: 'api/update_student_details.php',
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
    $.get('api/get_dashboard_stats.php')
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
    window.open(`api/export_data.php?type=${type}`, '_blank');
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
    $('.dashboard-layout').toggleClass('sidebar-collapsed');
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
