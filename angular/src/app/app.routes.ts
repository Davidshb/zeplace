import {Routes} from '@angular/router';
import {authGuard, authLoginGuard} from "@core/guards/auth.guard";

export const routes: Routes = [
  {
    path: 'login',
    title: 'Connexion',
    loadComponent: () => import('./pages/login/login.component').then(m => m.LoginComponent),
    canActivate: [authLoginGuard]
  },
  {
    path: '',
    title: 'Zeplace',
    loadComponent: () => import('./pages/home/home.component').then(m => m.HomeComponent),
    canActivate: [authGuard]
  }
];
