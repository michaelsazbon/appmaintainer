import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';


@Injectable()
export class HomeService {
  constructor(private http: HttpClient) { }

  
  executeScript(parametres : any) {
    return this.http.post<any>(`/api/script/execute`, parametres);
  }

  getScripts() {
    return this.http.get(`/api/script`);
  }

  
}