import {Injectable} from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import {TokenService} from "@core/services/token.service";
import {Router} from "@angular/router";

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private _isLoggedSubject = new BehaviorSubject<boolean>(this._tokenService.getToken() !== null);

  constructor(
    private readonly _tokenService: TokenService,
    private readonly _router: Router
  ) {
  }

  public isLogged() {
    return this._isLoggedSubject.asObservable()
  }

  public login(token: string) {
    this._tokenService.saveToken(token)
    this._isLoggedSubject.next(true)
  }

  public logout() {
    this._tokenService.cleanToken()
    this._isLoggedSubject.next(false)
    void this._router.navigate(['login'])
  }
}
