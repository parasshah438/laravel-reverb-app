<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InChat — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="container" style="max-width: 420px;">
        <div class="text-center mb-4">
            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px;">
                <i class="bi bi-chat-dots-fill text-primary" style="font-size:2rem;"></i>
            </div>
            <h2 class="text-white fw-bold">InChat</h2>
            <p class="text-white-50">Internal messaging system</p>
        </div>

        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-4 text-center text-muted">Sign in to your account</h5>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="/login">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium">Email address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-envelope text-muted"></i>
                            </span>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                   placeholder="you@chat.com"
                                   autofocus required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock text-muted"></i>
                            </span>
                            <input type="password"
                                   name="password"
                                   class="form-control border-start-0 ps-0"
                                   placeholder="••••••••"
                                   required>
                        </div>
                    </div>

                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-muted" for="remember">Remember me</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-4 text-center">
            <p class="text-white-50 small mb-2">Test accounts (password: <strong class="text-white">password</strong>)</p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                @foreach([['alice@chat.com','Alice'],['bob@chat.com','Bob'],['charlie@chat.com','Charlie'],['diana@chat.com','Diana'],['evan@chat.com','Evan']] as [$email, $name])
                <button type="button"
                        class="btn btn-sm btn-light btn-outline-light bg-white bg-opacity-10 text-white border-white border-opacity-25"
                        onclick="document.querySelector('[name=email]').value='{{ $email }}'; document.querySelector('[name=password]').value='password'">
                    {{ $name }}
                </button>
                @endforeach
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
