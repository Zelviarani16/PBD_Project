<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Super Admin</title>
  <link rel="stylesheet" href="/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="wrapper">
    <aside class="sidebar">
      <h2>SYMDASH</h2>
      <ul>
        <li><a href="/dashboard">Dashboard</a></li>
        <li><a href="/logout">Logout</a></li>
      </ul>
    </aside>

    <main class="content">
      @yield('content')
    </main>
  </div>
</body>
</html>
