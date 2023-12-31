import { Injectable } from '@angular/core';
import { ShoeHttpService } from '@core/services/shoe-http.service';

@Injectable({
  providedIn: 'root'
})
export class ShoeService {

  constructor(
    private readonly shoeHttpService: ShoeHttpService
  ) { }


}
