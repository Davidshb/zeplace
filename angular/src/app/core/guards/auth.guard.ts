import {CanActivateFn, Router} from '@angular/router';
import {inject} from "@angular/core";
import {AuthService} from "@core/services/auth.service";
import {map} from "rxjs";

export const authGuard: CanActivateFn = () => {
  const authService = inject(AuthService);
  const router = inject(Router);
  return authService.isLogged().pipe(
    map(isLogged => {

      if (!isLogged) {
        return router.parseUrl('/login')
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
