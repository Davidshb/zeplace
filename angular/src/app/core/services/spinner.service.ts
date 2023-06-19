import {Injectable} from '@angular/core';
import {BehaviorSubject, debounceTime} from "rxjs";
import {Event, NavigationEnd, NavigationStart, Router} from "@angular/router";

@Injectable({
  providedIn: 'root'
})
export class SpinnerService {

  private _spinner: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(false)
  private _timeoutId!: number

  constructor(private _router: Router) {
    this._router.events.subscribe((event: Event) => {
      if(event instanceof NavigationStart) {
        this.showGlobal()
      }

      if(event instanceof NavigationEnd) {
        this.hideGlobal()
      }
    })
  }

  public getGlobalSpinner() {
    return this._spinner.pipe(debounceTime(250))
  }

  public showGlobal() {
    clearTimeout(this._timeoutId)
    this._spinner.next(true)

    this._timeoutId = setTimeout(() => this._spinner.next(false), 30000)
  }

  public hideGlobal() {
    clearTimeout(this._timeoutId)
    this._spinner.next(false)
  }
}
