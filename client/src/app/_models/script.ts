import { QuestionBase } from "../question/question-base";
import { FormGroup, FormControl, Validators } from "@angular/forms";

export class Script {
    id: number;
    nom: string;
    description: string;
    chemin: string;
    parametres: QuestionBase<any>[];

    public toto()
    {
        console.log("toto");
    }
    
    get Parametres() {
        debugger;
        let group: any = {};

        this.parametres.forEach(question => {
            group[question.key] = question.required ? new FormControl(question.value || '', Validators.required)
                : new FormControl(question.value || '');
        });
        return new FormGroup(group);
    }

    public toFormGroup() {
        let group: any = {};

        this.parametres.forEach(question => {
            group[question.key] = question.required ? new FormControl(question.value || '', Validators.required)
                : new FormControl(question.value || '');
        });
        return new FormGroup(group);
    }
}