import { ShoeSize } from '@core/models/dto/shoe-size.model';

export interface SoldShoeDTO {
  id: number;
  name: string;
  imgUrl: string;
  soldDate: string;
  soldPrice: string;
  profit: string;
  profitVal: number;
  size: ShoeSize;
}

export interface SaleShoeDTO {
  id: number;
  name: string;
  imgUrl: string;
  purchaseDate: string;
  sellingPrice: string|null;
  size: ShoeSize;
}
