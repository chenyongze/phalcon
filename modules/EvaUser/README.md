EvaUser - Stardard user module of EvaEngine
=======

### Basic Interface

Get Current User basic info from Session

``` php
use Eva\EvaUser\Models\Login;
$user = Login::getCurrentUser();
$roles = Login::getCurrentUserRoles();
```

Get user info

### Login

System inner login.
``` php
$login = new Login();
$login->id = 1;
$user = $login->login();
```
System login will:

1. Check user exsits
2. Clear user login failde counter
3. Update user last login time
4. Save user info to Session

During login, below login Events will be triggered
- `user:beforeLogin`
- `user:afterLogin`


-----




Login by Username/Email & Password

``` php
$loginUser = new Login();
$loginUser->loginByPassword('username/email', 'password');
```

----

Login by Cookie Token

Cookie Token string looks like: `session_id|random_token|user_hash`

`random_token` be created when Cookie Token generated.

`user_hash` algorithm actually is `md5($salt . $user->status . $user->password)`, so if user change password or changed status(like be blocked), cookie token will be expired automatically.


``` php
$loginUser = new Login();
$loginUser->loginByCookie('cookie token string');
```

----
Login by Third part token

### Register


