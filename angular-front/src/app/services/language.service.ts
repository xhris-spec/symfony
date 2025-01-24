import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { TranslateService } from '@ngx-translate/core';

@Injectable({
  providedIn: 'root'
})
export class LanguageService {
  private language = new BehaviorSubject<string>('es');
  currentLanguage = this.language.asObservable();

  constructor(private translate: TranslateService) {
    translate.setDefaultLang('es');
  }

  changeLanguage(language: string) {
    this.language.next(language);
    this.translate.use(language);
  }
}
