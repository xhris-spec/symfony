import { Component, OnInit } from '@angular/core';
import { LanguageService } from 'src/app/services/language.service';


@Component({
  selector: 'app-nav',
  templateUrl: './nav.component.html',
})
export class NavComponent implements OnInit {

  constructor(private languageService: LanguageService) { }

  ngOnInit() {
  }

  changeLanguage(language: string) {
    this.languageService.changeLanguage(language);
  }
}
