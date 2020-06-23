import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DatastorageService {

  constructor(private httpClient: HttpClient) { }

  loadAllUser(): Observable<any> {
    // lädt aktuell nur den User mit der ID = 1. 
    return this.httpClient.get("/Perschke-Webanwendung/BACKEND/public/index.php/tasklists?user_id=1");
  }

  updateTasks(task) {
    this.httpClient.put("/Perschke-Webanwendung/BACKEND/public/index.php/task", task).subscribe(data => console.log(data));
  }
}
