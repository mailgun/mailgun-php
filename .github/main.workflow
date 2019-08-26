workflow "Main" {
  on = "push"
  resolves = ["Roave BC Check", "PHPStan"]
}

action "Roave BC Check" {
  uses = "docker://nyholm/roave-bc-check-ga"
  secrets = ["GITHUB_TOKEN"]
  args = ""
}

action "PHPStan" {
  uses = "docker://oskarstark/phpstan-ga"
  secrets = ["GITHUB_TOKEN"]
  args = "analyse"
}
