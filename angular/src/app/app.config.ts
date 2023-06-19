import {ApplicationConfig} from '@angular/core';
import {provideRouter} from '@angular/router';

import {routes} from './app.routes';
import {HTTP_INTERCEPTORS, provideHttpClient} from "@angular/common/http";
import {AuthInterceptor} from "@core/interceptors/auth.interceptor";

export const appConfig: ApplicationConfig = {
  providers: [
    provideHttpClient(),
    {provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true},
    provideRouter(routes)
  ]
};
