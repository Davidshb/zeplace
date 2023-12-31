import { Injectable } from '@angular/core';
import { EditShoeDetailsInputDTO, EditShoeDetailsOutputDTO } from '@core/models/dto/shoeDetails.model';
import {SoldShoeDTO, SaleShoeDTO} from "@core/models/dto/shoe.model";
import {AbstractHttpService} from "@core/services/abstract-http.service";

@Injectable({
  providedIn: 'root'
})
export class ShoeHttpService extends AbstractHttpService {

  public getSoldShoes() {
    return this._client.get<SoldShoeDTO[]>(`${this._apiUrl}/api/sneaker/sold`)
  }

  public getSaleShoes() {
    return this._client.get<SaleShoeDTO[]>(`${this._apiUrl}/api/sneaker/sale`)
  }

  public deleteShoe(id: number) {
    return this._client.delete<{response: boolean}>(`${this._apiUrl}/api/sneaker/${id}`)
  }

  public getEditShoeDetails(id: number) {
    return this._client.get<EditShoeDetailsInputDTO>(`${this._apiUrl}/api/sneaker/${id}`)
  }

  public putShoeDetails(id: number, shoe: EditShoeDetailsOutputDTO) {
    return this._client.put<{response: boolean}>(`${this._apiUrl}/api/sneaker/${id}`, shoe)
  }
}
