// MKCE Admission Portal - Main JavaScript

// Login functionality
$(document).ready(function() {
    // Initialize login form if exists
    if ($('#loginForm').length) {
        initializeLogin();
    }
    
    // Initialize admission dashboard if exists
    if ($('.dashboard-container').length) {
        initializeDashboard();
    }
});

// Login Functions
function initializeLogin() {
    $('#loginForm').on('submit', function(e){
        e.preventDefault();
        
        // Show loading state
        Swal.fire({
            title: 'Logging in...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: 'login.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response){
                if(response.status === 'success'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful!',
                        text: 'Welcome to MKCE Admission Portal',
                        timer: 1500,
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href = 'admission.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message || 'Invalid credentials'
                    });
                }
            },
            error: function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    text: 'Something went wrong. Please try again!'
                });
            }
        });
    });
}

// Dashboard Functions
function initializeDashboard() {
    initializeTabs();
    loadDashboardStats();
    initializeAdmissionForm();
    loadAdmissionsList();
    loadStudentsList();
}

// Tab Management
function initializeTabs() {
    $('.nav-tab').on('click', function() {
        const targetTab = $(this).data('tab');
        
        // Update active tab
        $('.nav-tab').removeClass('active');
        $(this).addClass('active');
        
        // Show target content
        $('.tab-content').removeClass('active');
        $('#' + targetTab).addClass('active');
        
        // Load specific content based on tab
        switch(targetTab) {
            case 'admissions-tab':
                loadAdmissionsList();
                break;
            case 'students-tab':
                loadStudentsList();
                break;
            case 'reports-tab':
                loadReports();
                break;
        }
    });
}

// Load Dashboard Statistics
function loadDashboardStats() {
    $.ajax({
        url: 'api/get_stats.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('.stat-card.admissions .stat-number').text(response.data.total_admissions || 0);
                $('.stat-card.pending .stat-number').text(response.data.pending || 0);
                $('.stat-card.confirmed .stat-number').text(response.data.confirmed || 0);
                $('.stat-card.rejected .stat-number').text(response.data.rejected || 0);
            }
        },
        error: function() {
            console.log('Error loading dashboard stats');
        }
    });
}

// Admission Form Management
function initializeAdmissionForm() {
    $('#admissionForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Show loading
        Swal.fire({
            title: 'Saving Admission Record...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: 'api/save_admission.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Admission record saved successfully'
                    }).then(() => {
                        $('#admissionForm')[0].reset();
                        loadAdmissionsList();
                        loadDashboardStats();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'Failed to save admission record'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong. Please try again!'
                });
            }
        });
    });
}

// Load Admissions List
function loadAdmissionsList() {
    $.ajax({
        url: 'api/get_admissions.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                displayAdmissionsList(response.data);
            }
        },
        error: function() {
            console.log('Error loading admissions list');
        }
    });
}

// Display Admissions List
function displayAdmissionsList(admissions) {
    let html = '';
    
    if (admissions.length === 0) {
        html = '<tr><td colspan="8" class="text-center">No admission records found</td></tr>';
    } else {
        admissions.forEach(function(admission) {
            html += `
                <tr>
                    <td>${admission.sid}</td>
                    <td>${admission.fname} ${admission.lname || ''}</td>
                    <td>${admission.programme}</td>
                    <td>${admission.department}</td>
                    <td>${admission.batch}</td>
                    <td>${formatDate(admission.doadmission)}</td>
                    <td><span class="status-badge status-${admission.status.toLowerCase()}">${admission.status}</span></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="confirmStudent(${admission.admission_id})">
                            Confirm
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="rejectAdmission(${admission.admission_id})">
                            Reject
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    $('#admissionsTableBody').html(html);
}

// Load Students List
function loadStudentsList() {
    $.ajax({
        url: 'api/get_students.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                displayStudentsList(response.data);
            }
        },
        error: function() {
            console.log('Error loading students list');
        }
    });
}

// Display Students List
function displayStudentsList(students) {
    let html = '';
    
    if (students.length === 0) {
        html = '<tr><td colspan="7" class="text-center">No student records found</td></tr>';
    } else {
        students.forEach(function(student) {
            html += `
                <tr>
                    <td>${student.sid}</td>
                    <td>${student.fname} ${student.lname || ''}</td>
                    <td>${student.programme}</td>
                    <td>${student.department}</td>
                    <td>${student.batch}</td>
                    <td>${student.mobile || 'N/A'}</td>
                    <td>${student.email || 'N/A'}</td>
                </tr>
            `;
        });
    }
    
    $('#studentsTableBody').html(html);
}

// Confirm Student
function confirmStudent(admissionId) {
    Swal.fire({
        title: 'Confirm Student?',
        text: "This will move the student to the confirmed list and create their student record.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, confirm!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api/confirm_student.php',
                type: 'POST',
                data: { admission_id: admissionId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Confirmed!',
                            text: 'Student has been confirmed successfully.'
                        });
                        loadAdmissionsList();
                        loadStudentsList();
                        loadDashboardStats();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to confirm student'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again!'
                    });
                }
            });
        }
    });
}

// Reject Admission
function rejectAdmission(admissionId) {
    Swal.fire({
        title: 'Reject Admission?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, reject!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api/reject_admission.php',
                type: 'POST',
                data: { admission_id: admissionId },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Rejected!',
                            text: 'Admission has been rejected.'
                        });
                        loadAdmissionsList();
                        loadDashboardStats();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to reject admission'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again!'
                    });
                }
            });
        }
    });
}

// Load Reports
function loadReports() {
    // Implementation for reports functionality
    console.log('Loading reports...');
}

// Logout Function
function confirmLogout() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out of the system",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    });
}

// Utility Functions
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB');
}

function generateSID(programme, department, batch) {
    // Generate SID based on pattern: YearProgrammeDeptSequence
    // Example: 26MKCEAL001 (2026 + MKCE + AL + 001)
    const year = batch.substring(2, 4); // Get last 2 digits of year
    const progCode = programme.substring(0, 4).toUpperCase();
    const deptCode = getDepartmentCode(department);
    
    // This would need to be generated server-side with proper sequence
    return `${year}${progCode}${deptCode}XXX`;
}

function getDepartmentCode(department) {
    const codes = {
        'Computer Science and Engineering': 'CS',
        'Electronics and Communication Engineering': 'EC',
        'Electrical and Electronics Engineering': 'EE',
        'Mechanical Engineering': 'ME',
        'Civil Engineering': 'CE',
        'Information Technology': 'IT',
        'Artificial Intelligence and Data Science': 'AD',
        'Computer Science and Business Systems': 'CB',
        'Artificial Intelligence and Machine Learning': 'AL'
    };
    return codes[department] || 'XX';
}

// Auto-fill SID when programme, department, and batch are selected
$(document).on('change', '#programme, #department, #batch', function() {
    const programme = $('#programme').val();
    const department = $('#department').val();
    const batch = $('#batch').val();
    
    if (programme && department && batch) {
        // This should call server-side function to generate proper SID
        const suggestedSID = generateSID(programme, department, batch);
        $('#sid').attr('placeholder', `Suggested: ${suggestedSID}`);
    }
});
