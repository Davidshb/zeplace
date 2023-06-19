import {Injectable} from '@angular/core';
import {BehaviorSubject, filter, Observable} from "rxjs";
import {TokenService} from "@core/services/token.service";
import {Router} from "@angular/router";

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private _isLoggedSubject: BehaviorSubject<boolean | null> = new BehaviorSubject<boolean | null>(null);

  private _isLoggingInSubject: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(false)

  constructor(
    private readonly _tokenService: TokenService,
    private readonly _router: Router
  ) {
    this._isLoggedSubject.next(_tokenService.getToken() !== null)
  }

  public isLogged() {
    return this._isLoggedSubject.asObservable().pipe(filter(data => data != null))
  }

  public getLoggingIn(): Observable<boolean> {
    return this._isLoggingInSubject.asObservable()
  }

  public login(token: string) {
    this._tokenService.saveToken(token)
    this._isLoggedSubject.next(true)
  }

  public logout() {
    this._isLoggedSubject.next(false)
    this._router.navigate(['login'])
  }
}
