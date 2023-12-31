import { inject } from '@angular/core';
import { HttpErrorResponse, HttpEvent, HttpHandlerFn, HttpInterceptorFn, HttpRequest } from '@angular/common/http';
import { ErrorCode } from '@core/enums/error-code';
import { catchError, first, Observable, of, throwError } from 'rxjs';
import { TokenService } from '@core/services/token.service';
import { NavigationExtras, Router } from '@angular/router';
import { AuthService } from '@core/services/auth.service';
import { PopUpService } from '@core/services/pop-up.service';
import { removeRedirectUri } from '../../helpers/url';

export const authInterceptor: HttpInterceptorFn = (request: HttpRequest<unknown>, next: HttpHandlerFn): Observable<HttpEvent<unknown>> => {
    const tokenService = inject(TokenService);
    const authService = inject(AuthService);
    const popUpService = inject(PopUpService);
    const router = inject(Router);

    const token = tokenService.getToken();

    const customReq = request.clone(
        token ? {headers: request.headers.set('Authorization', `Bearer ${token}`)} : {}
    );

    return next(customReq).pipe(
        catchError((error: HttpErrorResponse) => {
            if (error.status === 401 && !request.url.includes('login') && !router.routerState.snapshot.url.includes('/login')) {
                authService.isLogged().pipe(first()).subscribe(isLogged => {
                    if (isLogged) {
                        popUpService.onRequestError('Vous n\'êtes plus connecté');
                        authService.logout();
                    }
                });

                const redirectUri = removeRedirectUri(router.routerState.snapshot.url);
                const extras: NavigationExtras = {};

                if (redirectUri.length > 0) {
                    extras.queryParams = {
                        redirectUri
                    };
                }

                void router.navigate(['/login'], extras);
                return of();
            }

            error.error.forEach((err: { errorCode: ErrorCode, errorMessage: string }) => {
                popUpService.onRequestError(err.errorMessage);
            });

            return throwError(() => error);
        })
    );
};

