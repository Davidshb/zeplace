import { Component, Input } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { SoldShoeDTO } from '@core/models/dto/shoe.model';
import { Router, RouterLink } from '@angular/router';
import { PopUpService } from '@core/services/pop-up.service';
import { ShoesService } from '../../services/shoes.service';
import { SpinnerComponent } from '@shared/spinner/spinner.component';
import { BehaviorSubject, finalize } from 'rxjs';

@Component({
  selector: 'app-sold-shoe',
  standalone: true,
  imports: [CommonModule, SpinnerComponent, NgOptimizedImage, RouterLink],
  templateUrl: './sold-shoe.component.html',
  styleUrls: ['./sold-shoe.component.scss']
})
export class SoldShoeComponent {
  @Input() shoe!: SoldShoeDTO

  protected isDeleting$ = new BehaviorSubject(false)

  constructor(
    private readonly _router: Router,
    private readonly _popUpService: PopUpService,
    protected readonly _shoesService: ShoesService
  ) {
  }

  protected async deleteShoe() {
    const res = await this._popUpService.confirm(
      'Êtes-vous sûr de vouloir supprimer cette paire ?',
      'Elle sera supprimé de votre inventaire'
    )

    if (!res.isConfirmed) {
      return
    }

    this.isDeleting$.next(true)
    this._shoesService.deleteShoe(this.shoe.id)
      .pipe(
        finalize(() => {
          this.isDeleting$.next(false)
        })
      )
      .subscribe({
        next: ({response}) => {
          if (!response) {
            this._popUpService.onRequestError('la suppresion a échouée')
          }
        },
        error: err => {
          console.log(err)
          this._popUpService.onRequestError(err.error.message)
        }
      })
  }
}
