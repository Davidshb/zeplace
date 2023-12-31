import {environment} from "../../../environment/environment";
import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";

@Injectable({
  providedIn: 'root'
})
export abstract class AbstractHttpService {
  protected readonly _apiUrl = environment.apiUrl

  constructor(
    protected readonly _client: HttpClient
  ) {
  }

}
