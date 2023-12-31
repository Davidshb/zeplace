import {Routes} from '@angular/router';
import {authGuard, authLoginGuard} from "@core/guards/auth.guard";
import { shoeDetailsResolver } from './pages/edit-sneaker/resolvers/shoe-details.resolver';

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
    canActivate: [authGuard],
    children: [
      {
        path: '',
        loadComponent: () => import('./pages/home/home.component').then(m => m.HomeComponent),
        children: [
          {
            path: '',
            loadComponent: () => import('./pages/home/sale-shoes-tab/sale-shoes-tab.component').then(m => m.SaleShoesTabComponent)
          },
          {
            path: 'sold',
            loadComponent: () => import('./pages/home/sold-shoes-tab/sold-shoes-tab.component').then(m => m.SoldShoesTabComponent)
          }
        ]
      }, {
        path: 'add-sneaker',
        loadComponent: () => import('./pages/add-shoe/add-shoe.component').then(m => m.AddShoeComponent)
      }, {
        path: 'sneaker/:id/edit',
        loadComponent: () => import('./pages/edit-sneaker/edit-sneaker.component').then(m => m.EditSneakerComponent),
        resolve: {
          shoeDetails: shoeDetailsResolver
        }
      }
    ]
  }
];
