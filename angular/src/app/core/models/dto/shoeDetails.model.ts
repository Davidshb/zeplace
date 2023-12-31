import { AbstractControl } from '@angular/forms';
import { ShoeSize } from '@core/models/dto/shoe-size.model';

export interface EditShoeDetailsOutputDTO {
  purchaseDate: Date;
  purchasePrice: number;
  shippingCost: number;
  comment: string;
  size: ShoeSize;
  sellingPrice: number;
  sellingDate: Date|null;
}

export interface EditShoeDetailsInputDTO {
  id: number;
  title: string;
  imgUrl: string;
  purchaseDate: Date;
  purchasePrice: number;
  sellingDate: Date;
  shippingCost: number;
  comment: string;
  size: ShoeSize;
  sellingPrice: number;
}
