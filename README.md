# EXS-GoogleOauthInviteBundle
An extension of HWIO for authenticating with a registered Google account email. Any other accounts will receive an AccessDenied exception


## Routing
```
#app/config/routing.yml
## These should be before any custom routes
hwi_oauth_login:
    resource: "@HWIOAuthBundle/Resources/config/routing/login.xml"
    prefix:   /login
 
hwi_oauth_redirect:
    resource: "@HWIOAuthBundle/Resources/config/routing/redirect.xml"
    prefix:   /connect
 
google_login:
    pattern: /login/check-google
 
logout:
    path:   /logout
#...
```

## Services
```
#app/config/services.yml
services:
#...
    hwi_oauth.user.provider.entity:
        class: HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider
    exs_user.oauth_user_provider:
        class: EXS\GoogleOauthInviteBundle\Service\Auth\OAuthUserProvider
        arguments: [@doctrine.orm.entity_manager]
```


## Security
```
#app/config/security.yml
#...
    providers:
        my_custom_hwi_provider:
            id: exs_user.oauth_user_provider
    encoders:
        EXS\GoogleOauthInviteBundle\Entity\ExsUser:
            algorithm:        sha512
            encode_as_base64: false
            iterations: 
#...
    firewalls:
        main:
#...           
            oauth:
                resource_owners:
                    google: "/login/check-google"
                login_path: /
                failure_path: /
                default_target_path: /admin
                oauth_user_provider:
                    service: exs_user.oauth_user_provider
```
