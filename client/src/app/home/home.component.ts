
import { Component, OnInit, ViewChild, Input } from '@angular/core';
import { NgbTypeahead } from '@ng-bootstrap/ng-bootstrap';
import { HomeService } from './home.service';

import { Observable, Subject, merge } from 'rxjs';
import { debounceTime, distinctUntilChanged, filter, map } from 'rxjs/operators';
import { QuestionBase } from '../question/question-base';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { Script } from '../_models/script';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  providers: [HomeService],
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  constructor(private homeService: HomeService) { }

  public model: Script;
  toto: any;
  execute_env: String;
  scripts: Script[];
  errorMessage: String;

  @ViewChild('instance') instance: NgbTypeahead;
  focus$ = new Subject<string>();
  click$ = new Subject<string>();

  form: FormGroup;
  payLoad: any;

  onSubmit() {
    this.executeScript();
  }

 
  search = (text$: Observable<string>) => {

    const debouncedText$ = text$.pipe(debounceTime(200), distinctUntilChanged());
    const inputFocus$ = this.focus$;

    return merge(debouncedText$, inputFocus$).pipe(
      map(term => term === '' ? this.scripts
        : this.scripts.filter(v => v.nom.toLowerCase().indexOf(term.toLowerCase()) > -1).slice(0, 10))
    )

  }

  selectedItem(item){
    var questions = item.item.parametres;
    let group: any = {};
    //debugger;

    questions.forEach(question => {
      group[question.key] = question.required ? new FormControl(question.value || '', Validators.required)
                                              : new FormControl(question.value || '');
    });

    group["scriptname"] = new FormControl(item.item.chemin);

    this.form = new FormGroup(group);
  }


  public toFormGroup(questions: QuestionBase<any>[]) {
    let group: any = {};
    //debugger;

    questions.forEach(question => {
      group[question.key] = question.required ? new FormControl(question.value || '', Validators.required)
                                              : new FormControl(question.value || '');
    });

    this.form = new FormGroup(group);
    return this.form;
  }

  formatter = (x: { nom: string }) => x.nom;

  executeScript() {
    this.payLoad = null;
    var values = this.form.value;
    values["env"] = this.execute_env;
    //this.homeService.executeScript(this.form.value)
    this.homeService.executeScript(values)
      .subscribe(
        (res: any) => { 
          this.payLoad = res;
          /*if(res.status == 0) {
            this.payLoad = '<div class="alert alert-success">script éxécuté avec succès</div>';
          } else {
            this.payLoad = '<div class="alert alert-danger">Une erreur est survenue lors de l\'éxécution du script : '+ JSON.stringify(res.result)+'</div>';
          }*/
         },
        error => { this.errorMessage = error.message || error }
      );
  }

  getScripts() {
    this.homeService.getScripts()
      .subscribe(
        (res: any) => { this.scripts = res; console.log(this.scripts); },
        error => { this.errorMessage = error.message || error }
      );
  }

  ngOnInit() {
   // debugger;
    this.getScripts();
  }

}
