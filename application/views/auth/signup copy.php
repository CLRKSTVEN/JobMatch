<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - JobMatch DavOr</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", sans-serif;
        }

        .signup-card {
            max-width: 450px;
            margin: auto;
            border-radius: 8px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #2563eb;
        }

        .btn-primary {
            border-radius: 6px;
        }

        a {
            color: #2563eb;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .small-note {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>

<body>

    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card signup-card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="text-center mb-3">Create an Account</h3>

                <!-- Validation Errors -->
                <?= validation_errors('<div class="alert alert-warning small mb-3">', '</div>') ?>

                <!-- Signup Form -->
                <?= form_open('auth/signup') ?>
                <div class="mb-3">
                    <label for="first_name" class="form-label small">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control form-control-lg" placeholder="Enter first name" required>
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label small">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control form-control-lg" placeholder="Enter last name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label small">Email</label>
                    <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Enter email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small">Password</label>
                    <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Enter password" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label small">Register as</label>
                    <select name="role" id="role" class="form-select form-select-lg" required>
                        <option value="">-- Select Role --</option>
                        <option value="worker">Skilled Worker</option>
                        <option value="client">Individual / Employer</option>
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Sign Up</button>
                </div>
                <?= form_close() ?>

                <!-- Login Link -->
                <p class="text-center mt-3 mb-0 small">
                    Already have an account? <a href="<?= site_url('auth/login') ?>">Login here</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>