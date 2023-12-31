import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { Event, NavigationCancel, NavigationEnd, NavigationStart, Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class SpinnerService {

  private _spinner: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(false);
  private _timeoutId!: ReturnType<typeof setTimeout>;

  constructor(private _router: Router) {
    this._router.events.subscribe((event: Event) => {
      if (event instanceof NavigationStart) {
        this.showGlobal();
      }

      if (event instanceof NavigationEnd || event instanceof NavigationCancel) {
        this.hideGlobal();
      }
    });
  }

  public getGlobalSpinner() {
    return this._spinner.asObservable();
  }

  public showGlobal() {
    clearTimeout(this._timeoutId);
    this._spinner.next(true);

    this._timeoutId = setTimeout(() => this._spinner.next(false), 30000);
  }

  public hideGlobal() {
    clearTimeout(this._timeoutId);
    this._spinner.next(false);
  }
}
