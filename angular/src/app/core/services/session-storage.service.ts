import {Injectable} from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class SessionStorageService {
  public save(key: string, value: string) {
    sessionStorage.setItem(key, value)
  }

  public get(key: string) {
    return sessionStorage.getItem(key)
  }
}
