import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {first, Observable} from "rxjs";
import {environment} from "../../../environment/environment";
import {TokenDTO} from "@core/models/dto/token.model";
import {LoginDTO} from "@core/models/dto/loginData.model";

@Injectable({
  providedIn: 'root'
})
export class AuthHttpService {

  private readonly _apiUrl = environment.apiUrl

  constructor(
    private readonly _client: HttpClient
  ) { }

  public login(loginData: LoginDTO): Observable<TokenDTO> {
    return this._client.post<TokenDTO>(`${this._apiUrl}/login`, loginData, {
      headers: new HttpHeaders({'Content-Type': 'application/json'})
    }).pipe(first())
  }
}
