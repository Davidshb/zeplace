import {Injectable} from '@angular/core';
import {HttpErrorResponse, HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from '@angular/common/http';
import {catchError, first, Observable} from 'rxjs';
import {TokenService} from "@core/services/token.service";
import {Router} from "@angular/router";
import {AuthService} from "@core/services/auth.service";

@Injectable()
export class AuthInterceptor implements HttpInterceptor {

  constructor(
    private readonly _tokenService: TokenService,
    private readonly _router: Router,
    private readonly _authService: AuthService
  ) {
  }

  intercept(request: HttpRequest<unknown>, next: HttpHandler): Observable<HttpEvent<unknown>> {
    const token = this._tokenService.getToken()

    const customReq = request.clone(
      token ? {headers: request.headers.set('Authorization', `Bearer ${token}`)} : {}
    );

    return next.handle(customReq).pipe(
      catchError((error: HttpErrorResponse, caught) => {

        if (error.status === 401 && !request.url.includes('login') && !this._router.routerState.snapshot.url.includes('/login')) {
          this._authService.isLogged().pipe(first()).subscribe(isLogged => {
            if (isLogged) {
              this._authService.logout()
            }
          })
        }

        return caught
      })
    )
  }
}
