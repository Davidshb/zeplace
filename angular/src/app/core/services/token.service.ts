import {Injectable} from '@angular/core';
import {SessionStorageService} from "@core/services/session-storage.service";

@Injectable({
  providedIn: 'root'
})
export class TokenService {

  private readonly _tokenKey = 'token'

  constructor(
    private readonly _sessionStorageService: SessionStorageService
  ) {
  }

  public getToken(): string|null {
    const token = this._sessionStorageService.get(this._tokenKey)

    return token && !this._checkTokenExpired(token) ? token : null
  }

  public saveToken(token: string) {
    this._sessionStorageService.save(this._tokenKey, token)
  }

  private _parseToken(token: string) {
    return JSON.parse(atob(token.split('.')[1]))
  }

  private _checkTokenExpired(token: string) {
    try {
      const expiry = this._parseToken(token).exp
      return Math.floor((new Date()).getTime() / 1000) >= expiry
    } catch (error) {
      return true
    }
  }
}
