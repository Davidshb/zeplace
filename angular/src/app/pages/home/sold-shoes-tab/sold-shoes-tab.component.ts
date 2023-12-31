import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SoldShoeDTO } from '@core/models/dto/shoe.model';
import { Observable } from 'rxjs';
import { SoldShoeComponent } from '../components/sold-shoe/sold-shoe.component';
import { ShoesService } from '../services/shoes.service';

@Component({
  selector: 'app-sold-sold-shoe-tab',
  standalone: true,
  imports: [CommonModule, SoldShoeComponent],
  templateUrl: './sold-shoes-tab.component.html',
  styleUrls: ['./sold-shoes-tab.component.scss']
})
export class SoldShoesTabComponent implements OnInit {

  protected shoes!: Observable<SoldShoeDTO[]>

  constructor(
    private _shoeService: ShoesService
  ) {
  }
  ngOnInit() {
    this.shoes = this._shoeService.getSoldShoes()
  }
}
