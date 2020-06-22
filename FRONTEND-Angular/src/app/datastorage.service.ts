import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DatastorageService {

  constructor(private httpClient: HttpClient) { }

  loadAllUser(): Observable<any> {
    return this.httpClient.get("http://localhost/Perschke-Webanwendung/BACKEND/public/tasklists?user_id=1");
  }
}
