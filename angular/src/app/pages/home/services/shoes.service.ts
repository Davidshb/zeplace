import { Injectable } from '@angular/core';
import { AuthService } from '@core/services/auth.service';
import { ShoeHttpService } from '@core/services/shoe-http.service';
import { SaleShoeDTO, SoldShoeDTO } from '@core/models/dto/shoe.model';
import { SpinnerService } from '@core/services/spinner.service';
import { BehaviorSubject, finalize, map, Observable, tap } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ShoesService {

  private soldShoes$ = new BehaviorSubject<SoldShoeDTO[]>([]);
  private saleShoes$ = new BehaviorSubject<SaleShoeDTO[]>([]);

  private _soldShoesInit = false;
  private _saleShoesInit = false;

  private isRequesting$ = new BehaviorSubject(0);

  constructor(
    private readonly _shoeHttpService: ShoeHttpService,
    private readonly _spinnerService: SpinnerService,
    authService: AuthService
  ) {
    authService.isLogged().subscribe(logged => {
      if (!logged) {
        this._saleShoesInit = false
        this._soldShoesInit = false
      }
    })
  }

  private requesting() {
    this.isRequesting$.next(this.isRequesting$.value + 1);
  }

  private endRequesting() {
    this.isRequesting$.next(this.isRequesting$.value - 1);
  }

  public isRequesting(): Observable<boolean> {
    return this.isRequesting$.pipe(map(value => value !== 0));
  }

  public getSaleShoes(): Observable<SaleShoeDTO[]> {
    if (!this._saleShoesInit) {
      this.reloadSaleShoes()
      this._saleShoesInit = true
    }
    return this.saleShoes$.asObservable();
  }

  public reloadSoldShoes() {
    this.requesting();
    this._spinnerService.showGlobal();
    this._shoeHttpService.getSoldShoes()
      .pipe(
        finalize(() => this.endRequesting()),
        finalize(() => this._spinnerService.hideGlobal())
      )
      .subscribe(shoesDTO => this.soldShoes$.next(shoesDTO));
  }

  public reloadSaleShoes() {
    this.requesting();
    this._spinnerService.showGlobal();
    this._shoeHttpService.getSaleShoes()
      .pipe(
        finalize(() => this.endRequesting()),
        finalize(() => this._spinnerService.hideGlobal())
      )
      .subscribe(shoesDTO => this.saleShoes$.next(shoesDTO));
  }

  public getSoldShoes(): Observable<SoldShoeDTO[]> {
    if (!this._soldShoesInit) {
      this.reloadSoldShoes();
      this._soldShoesInit = true;
    }

    return this.soldShoes$.asObservable();
  }

  public deleteShoe(id: number): Observable<{response: boolean}> {
    this.requesting();
    return this._shoeHttpService.deleteShoe(id)
      .pipe(
        tap(({response}) => {
          if (response) {
            let shoes = this.soldShoes$.value;
            const index = shoes.findIndex(shoe => shoe.id === id);

            if (index === -1) {
              return;
            }

            shoes.splice(index, 1);
            this.soldShoes$.next(shoes);
          }
        }),
        finalize(() => this.endRequesting())
      );
  }
}
