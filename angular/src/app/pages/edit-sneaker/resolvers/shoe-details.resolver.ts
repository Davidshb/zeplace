import { inject } from '@angular/core';
import { ResolveFn, Router } from '@angular/router';
import { EditShoeDetailsInputDTO } from '@core/models/dto/shoeDetails.model';
import { PopUpService } from '@core/services/pop-up.service';
import { ShoeHttpService } from '@core/services/shoe-http.service';
import { catchError, of } from 'rxjs';

export const shoeDetailsResolver: ResolveFn<EditShoeDetailsInputDTO> = route => {
  const shoeHttpService = inject(ShoeHttpService)
  const router = inject(Router)

  const popUpService = inject(PopUpService)

  return shoeHttpService.getEditShoeDetails(route.params['id'])
    .pipe(catchError(() => {
      popUpService.onRequestError('Impossible de récupérer les données de la paire')
      void router.navigate(['/'])
      return of()
    }))
};
