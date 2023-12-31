import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SaleShoeDTO } from '@core/models/dto/shoe.model';
import { Observable } from 'rxjs';
import { SaleShoeComponent } from '../components/sale-shoe/sale-shoe.component';
import { ShoesService } from '../services/shoes.service';

@Component({
  selector: 'app-sale-sold-shoe-tab',
  standalone: true,
    imports: [CommonModule, SaleShoeComponent],
  templateUrl: './sale-shoes-tab.component.html',
  styleUrls: ['./sale-shoes-tab.component.scss']
})
export class SaleShoesTabComponent implements OnInit{
  protected shoes!: Observable<SaleShoeDTO[]>

  constructor(
    private _shoesService: ShoesService
  ) {
  }

  ngOnInit() {
    this.shoes = this._shoesService.getSaleShoes();
  }
}
