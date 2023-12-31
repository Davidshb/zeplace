import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { SessionStorageService } from '@core/services/session-storage.service';
import { Observable, tap } from 'rxjs';
import { TokenDTO } from '@core/models/dto/token.model';
import { LoginDTO } from '@core/models/dto/loginData.model';
import { AbstractHttpService } from '@core/services/abstract-http.service';

@Injectable({
  providedIn: 'root'
})
export class AuthHttpService extends AbstractHttpService {

  constructor(
    client: HttpClient,
    private readonly sessionStorageService: SessionStorageService
  ) {
   super(client)
  }

  public login(loginData: LoginDTO): Observable<TokenDTO> {
    return this._client.post<TokenDTO>(`${this._apiUrl}/api/login`, loginData, {
      headers: new HttpHeaders({'Content-Type': 'application/json'})
    })
      .pipe(
        tap(() => this.sessionStorageService.save('username', loginData.username))
      )
  }
}
