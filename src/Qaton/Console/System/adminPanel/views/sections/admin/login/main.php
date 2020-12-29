<form class="form-signin" method="post" action="<?php $this->baseUrl() ?>admin/login">
    <h1 class="h3 mb-3 font-weight-normal">Admin Panel</h1>
    <?php if ($login === false) : ?>
        <p class="alert alert-danger">Access Denied!</p>
    <?php endif; ?>
    <label class="sr-only" for="inputUsername">Username</label>
    <input autofocus="" class="form-control" id="inputUsername" placeholder="Username" required="" type="text" name="username"> 
    <label class="sr-only" for="inputPassword">Password</label>
    <input class="form-control" id="inputPassword" placeholder="Password" required="" type="password" name="password">
    <div class="checkbox mb-3">
        <!-- <label><input type="checkbox" value="remember-me"> Remember me</label> -->
    </div><button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
    <hr>
    <span class="text-muted">Powered By <a target="_blank" href="http://qaton.virx.net">VirX Qaton</a> by Antony Shan Peiris</span>
</form>