import {CanActivateFn, Router} from '@angular/router';
import {inject} from "@angular/core";
import {AuthService} from "@core/services/auth.service";
import {map} from "rxjs";
import {removeRedirectUri} from "../../helpers/url";

export const authGuard: CanActivateFn = () => {
  const authService = inject(AuthService);
  const router = inject(Router);
  return authService.isLogged().pipe(
    map(isLogged => {

      if (!isLogged) {
        let redirect = removeRedirectUri(router.routerState.snapshot.url)

        if (redirect !== '') {
          redirect = `?redirectUri=${redirect}`
        }

        return router.parseUrl(`/login${redirect}`)
      }

      return true
    })
  )
};

export const authLoginGuard: CanActivateFn = () => {
  const authService = inject(AuthService);
  const router = inject(Router);
  return authService.isLogged().pipe(
    map(isLogged => {
      if (isLogged) {
        return router.parseUrl('/')
      }

      return true
    })
  )
}
